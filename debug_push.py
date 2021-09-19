import json
import logging
import os
import socket
import ssl
import struct
import sys
import time
import uuid
import argparse

APNS_HOST = 'gateway.push.apple.com'
APNS_HOST_SANDBOX = 'gateway.sandbox.push.apple.com'
APNS_PORT = 2195

APNS_ERRORS = {
    1:'Processing error',
    2:'Missing device token',
    3:'missing topic',
    4:'missing payload',
    5:'invalid token size',
    6:'invalid topic size',
    7:'invalid payload size',
    8:'invalid token',
    255:'Unknown'
}

def push(cert_path, device, sandbox):
    if not os.path.exists(cert_path):
        logging.error("Invalid certificate path: %s" % cert_path)
        sys.exit(1)

    device = device.decode('hex')
    expiry = time.time() + 3600

    try:
        sock = ssl.wrap_socket(
            socket.socket(socket.AF_INET, socket.SOCK_STREAM),
            certfile=cert_path
        )
        host = APNS_HOST_SANDBOX if sandbox else APNS_HOST
        sock.connect((host, APNS_PORT))
        sock.settimeout(1)
    except Exception as e:
        logging.error("Failed to connect: %s" % e)
        sys.exit(1)

    logging.info("Connected to APNS\n")

    for ident in range(1,4):
        logging.info("Sending %d of 3 push notifications" % ident)

        payload = json.dumps({
            'aps': {
                'alert': 'Push Test %d: %s' % (ident, str(uuid.uuid4())[:8])
            }
        })

        items = [1, ident, expiry, 32, device, len(payload), payload]

        try:
            sent = sock.write(struct.pack('!BIIH32sH%ds'%len(payload), *items))
            if sent:
                logging.info("Message sent\n")
            else:
                logging.error("Unable to send message\n")
        except socket.error as e:
            logging.error("Socket write error: %s", e)

        # If there was an error sending, we will get a response on socket
        try:
            response = sock.read(6)
            command, status, failed_ident = struct.unpack('!BBI',response[:6])
            logging.info("APNS Error: %s\n", APNS_ERRORS.get(status))
            sys.exit(1)
        except socket.timeout:
            pass
        except ssl.SSLError:
            pass

    sock.close()

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description="Send test APNS notifications to device using cert")
    parser.add_argument("certificate", help="Push certificate")
    parser.add_argument("device_id", help="Device ID")
    parser.add_argument("-s", "--sandbox", action="store_true", help="Use APNS sandbox environment")
    args = parser.parse_args()

    logging.basicConfig(level=logging.INFO)
    push(args.certificate, args.device_id, args.sandbox)
    logging.info("Complete\n")