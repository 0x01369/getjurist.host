<?php

define('CHAT_HOST', 'api.bitfriend.pro');
define('SOSED_HOST', 'http://localhost/armfriend/');
define('AVATAR_HOST', 'http://api.bitfriend.pro/img/avatar/small/');

class BotController extends AdminController{

    private $chatConn;

    public function beforeAction($action){
        if(Yii::app()->user->checkAccess('moderator'))
            return false;
        return parent::beforeAction($action);
    }

    private function getChatConn(){
        if(!$this->chatConn){
            //$this->chatConn = new CDbConnection('mysql:host=localhost;dbname=ejabberd', 'ejabberd', 'd8bE5kH6');
            $this->chatConn = new CDbConnection('mysql:host=localhost;dbname=ejabberd', 'root', 'sd3bdNg7sk');
        }
        return $this->chatConn;
    }

    private function getBots(){

        $chatConn = $this->getChatConn();


        $where = 'u.bot_uid != 0';
        if(Yii::app()->user->checkAccess('bot')){
            $where = 'u.bot_uid = '.Yii::app()->user->id;
        }

        $sql = "SELECT u.id, u.mail, u.name, u.avatar, ".
            "(SELECT count(1) from friends f where f.user_id = u.id and f.confirmed = 1) as friends_count ".
            "FROM users u WHERE ".$where;
        $command = Yii::app()->db->createCommand($sql);
        $bots = $command->queryAll();


        foreach($bots as $i => $b){
            if (preg_match('/\d{4}\/\d{2}\/\d{2}\/[a-zA-Z0-9]{32}/', $b['avatar'])) {
                $path = 'http://api.bitfriend.pro/files/images/avatar/small/';
            } else {
                $path = 'http://api.bitfriend.pro/img/avatar/small/';
            }

            $bots[$i]['avatar'] = $path.$b['avatar'];

            $sql = 'SELECT count(1) as count FROM archive_messages m, archive_collections c '.
                'WHERE c.us = :id AND c.deleted = 0 AND c.id = m.coll_id AND m.dir = 0 AND m.deleted = 0 AND m.read_state = 0';
            $command = $chatConn->createCommand($sql);
            $p = $b['id'].'@'.CHAT_HOST;
            $command->bindParam(":id", $p, PDO::PARAM_STR);
            $bots[$i]['count'] = $bots[$i]['friends_count'] + $command->queryScalar();
        }

        function cmp($a, $b){
            if($a['count'] == $b['count'])
                return ($a['name'] < $b['name']) ? -1 : 1;
            return ($a['count'] > $b['count']) ? -1 : 1;
        }

        usort($bots, "cmp");

        return $bots;
    }

    public function actionGetBots($bot_id = null) {

        $bots = $this->getBots();

        $bot = array();
        $bot['username'] = $bot_id;

        $this->renderPartial('bot_list', array(
            'bots' => $bots,
            'bot' => $bot));
    }

    public function actionSearchUsers($key, $query) {
        $query = urlencode($query);
        $users = @file_get_contents(SOSED_HOST.'friends/search_users?key='.$key.'&query='.$query);
        //echo SOSED_HOST.'friends/search_users?key='.$key.'&query='.$query;
        $users = @json_decode($users);
        if($users === null) die('Ошибка');
        $users = $users->response;

        $this->renderPartial('users_list', array(
            'users' => $users,
        ));
    }

    public function actionSearchPlaces($key, $query) {
        $query = urlencode($query);
        $places = @file_get_contents(SOSED_HOST.'map/get_places?key='.$key.'&name='.$query.'&lat=0&lon=0');
        $places = @json_decode($places);
        if($places === null) die('Ошибка');
        if(isset($places->error)) die($places->error->message);
        $places = $places->response;

        $this->renderPartial('places_list', array(
            'places' => $places,
        ));
    }

    public function actionGetName($id){
        $sql = "SELECT name FROM users WHERE id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":id", $id, PDO::PARAM_STR);
        echo $command->queryScalar();
    }

    public function actionIndex($bot_id = null) {

        $bot = false;
        $dialogs = array();
        $friends = array();

        if($bot_id != null){
            $chatConn = $this->getChatConn();
            $sql = "SELECT username, password FROM users WHERE username = :id";
            $command = $chatConn->createCommand($sql);
            $command->bindParam(":id", $bot_id, PDO::PARAM_STR);
            $bot = $command->queryRow();

            $sql = "SELECT u.id, u.mail, u.name, ".
                "(SELECT count(1) from friends f where f.user_id = u.id and f.confirmed = 1) as friends_count ".
                "FROM users u WHERE u.id = ".$bot_id;
            $command = Yii::app()->db->createCommand($sql);
            $bot_mail = $command->queryAll();

            if(count($bot_mail) == 0) die('Error');
            $bot['mail'] = $bot_mail[0]['mail'];
            $bot['name'] = $bot_mail[0]['name'];
            $bot['friends_count'] = $bot_mail[0]['friends_count'];

            $r = @file_get_contents(SOSED_HOST.'profile/login?mail='.$bot['mail'].'&password='.$bot['password']);
            $r = @json_decode($r);
            if($r === null) die('Ошибка');
            if(isset($r->error)) die($r->error->message);
            $bot['key'] = $r->response->key;

            $dialogs = @file_get_contents(SOSED_HOST.'message/get_dialogs?key='.$bot['key'].'&count=50');
            $dialogs = @json_decode($dialogs);
            if($dialogs === null) die('Ошибка');
            if(isset($r->error)) die($r->error->message);
            $dialogs = $dialogs->response;

            $friends = @file_get_contents(SOSED_HOST.'friends/get?key='.$bot['key'].'&count=50');
            $friends = @json_decode($friends);
            if($friends === null) die('Ошибка');
            if(isset($r->error)) die($r->error->message);
            $friends = $friends->response;
        }

        $this->render('bots', array(
            'bot' => $bot,
            'dialogs' => $dialogs,
            'friends' => $friends));
    }

    public function actionSendPost($key){
        $message = '';
        if(isset($_POST['yt0'])){
            $key = $_POST['key'];
            $place_id = $_POST['place_id'];
            $file = '';
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $file = $_FILES['image']['tmp_name'];

                $data = exec('identify -format "%e %w %h" '.$file);
                $params = explode(' ', $data);
                $w = $params[1];
                $h = $params[2];

                $resize = $w;
                $size = $w.'x'.$w;
                if($h < $w){
                    $resize = 'x'.$h;
                    $size = $h.'x'.$h;
                }

                exec('convert '.$file.' -resize '.$resize.' -gravity center -crop '.$size.'+0+0 '.$file);
            }

            $fields = array();
            $fields['text'] = $_POST['text'];
            if($file)
                $fields['image'] = '@'.$file;
            if($place_id)
                $fields['place_id'] = $place_id;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, SOSED_HOST.'post/create?key='.$key);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $resp = curl_exec($ch);
            curl_close($ch);

            $resp = @json_decode($resp);
            if($resp === null) {
                $message = 'Ошибка';
            } else if(isset($resp->error)) {
                $message = $resp->error->message;
            } else {
                $message = 'Сообщение успешно отправлено';
            }
        }

        $this->render('send_post', array('key' => $key, 'message' => $message));
    }

    public function actionAddCheckin($key){
        $this->render('places', array('key' => $key));
    }
}
