import requests
import cv2
from cvzone.HandTrackingModule import HandDetector

detector = HandDetector(detectionCon=0.8, maxHands=1)
video = cv2.VideoCapture(0)

# IP ESP8266
esp_ip = "http://192.168.178.80/leds"

# Mapping jumlah jari ke status LED
finger_states = {
    "[0, 1, 0, 0, 0]": "red",     
    "[0, 1, 1, 0, 0]": "yellow",  
    "[0, 1, 1, 1, 0]": "green",   
    "[0, 1, 1, 1, 1]": "all",
    "[1, 1, 1, 1, 1]": "off",
    # "[0, 0, 0, 0, 1]": "twinkle",
}

while True:
    ret, frame = video.read()
    frame = cv2.flip(frame, 1)
    hands, img = detector.findHands(frame)

    if hands:
        lmList = hands[0]
        fingerUp = detector.fingersUp(lmList)

        print(f"Detected fingers: {fingerUp}")
        state = finger_states.get(str(fingerUp))
        if state:
            try:
                response = requests.post(esp_ip, data={"state": state})
                print(f"Sent state: {state}, Response: {response.status_code}")
            except requests.exceptions.RequestException as e:
                print(f"Error sending to ESP8266: {e}")
    
        cv2.putText(frame, f"Finger count: {fingerUp.count(1)}",
                    (20, 460), cv2.FONT_HERSHEY_COMPLEX, 1, (255, 255, 255), 1, cv2.LINE_AA)

    cv2.imshow("frame", frame)
    q = cv2.waitKey(1)
    if q == ord("q"):
        break

video.release()
cv2.destroyAllWindows()
