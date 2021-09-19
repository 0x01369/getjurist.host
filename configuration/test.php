<?php

$t1 = microtime(true);

$pdo = new PDO('mysql:host=141.105.65.221;dbname=armfriend', 'root', 'IIct12vm072', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]);

$t2 = microtime(true);

echo "Init\n";
//var_dump(round($t2 - $t1, 2));

echo "----------------\n\n";

$sql = "SELECT 1 FROM user_block WHERE block_uid = '8968' and uid = '26059';";
//$sql = "SELECT p.id as post_id, p.type, p.uid, p.title, p.text, p.image, p.image_aspect, p.checkin_id, p.date, count(DISTINCT post_comment_a.id) as comment_count, count(DISTINCT post_likes_a.uid) as likes, p.date_start, p.date_finish , sum(IF(`pc`.id is null,0, 1)) as `unread_count` FROM post p  inner join `post_comment_watchers` pcw on pcw.post_id = p.id  LEFT JOIN (SELECT post_id, MAX(date) AS date FROM post_comment WHERE uid = '37942' GROUP BY post_id) AS lpc ON lpc.post_id = p.id  left join post_comment as pc on pc.post_id = p.id and pc.post_id > pcw.last_seen_comment and pc.deleted = 0  LEFT JOIN post_comment post_comment_a on p.id = post_comment_a.post_id AND post_comment_a.deleted = 0 LEFT JOIN post_likes post_likes_a on p.id = post_likes_a.post_id WHERE p.deleted = 0  AND pcw.uid = '37942'  GROUP BY `p`.id ORDER BY IF(p.date > lpc.date OR lpc.date IS NULL, p.date, lpc.date) DESC  LIMIT 0, 20";
//$sql = "SELECT p.id as post_id, p.type, p.uid, p.title, p.text, p.image, p.image_aspect, p.checkin_id, p.date, count(DISTINCT post_comment_a.id) as comment_count, count(DISTINCT post_likes_a.uid) as likes, p.date_start, p.date_finish , sum(IF(`pc`.id is null,0, 1)) as `unread_count` FROM post p  inner join `post_comment_watchers` pcw on pcw.post_id = p.id  LEFT JOIN (SELECT post_id, MAX(date) AS date FROM post_comment WHERE uid = '20817' GROUP BY post_id) AS lpc ON lpc.post_id = p.id  left join post_comment as pc on pc.post_id = p.id and pc.post_id > pcw.last_seen_comment and pc.deleted = 0  LEFT JOIN post_comment post_comment_a on p.id = post_comment_a.post_id AND post_comment_a.deleted = 0 LEFT JOIN post_likes post_likes_a on p.id = post_likes_a.post_id WHERE p.deleted = 0  AND pcw.uid = '20817'  GROUP BY `p`.id ORDER BY IF(p.date > lpc.date OR lpc.date IS NULL, p.date, lpc.date) DESC  LIMIT 0, 20";

$stmt = $pdo->prepare($sql);

$t3 = microtime(true);

echo "Prepare\n";
//var_dump(round($t3 - $t1, 2));

echo "----------------\n\n";

$stmt->execute();

$t4 = microtime(true);

echo "Execute\n";
//var_dump(round($t4 - $t1, 2));

echo "----------------\n\n";

$stmt->fetchAll(PDO::FETCH_OBJ);

$t5 = microtime(true);

echo "Fetch Objects\n";
//var_dump(round($t5 - $t1, 2));