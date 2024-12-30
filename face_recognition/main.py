import cv2
import face_recognition
import os
from pathlib import Path
import numpy as np
import threading
from queue import Queue


def load_known_faces(images_folder):
    known_face_encodings = []
    known_face_names = []

    images_path = Path(images_folder)
    valid_extensions = ['.jpg', '.jpeg', '.png']

    for image_file in images_path.iterdir():
        if image_file.suffix.lower() in valid_extensions:
            name = image_file.stem

            image = face_recognition.load_image_file(str(image_file))
            face_encodings = face_recognition.face_encodings(image)

            if face_encodings:
                known_face_encodings.append(face_encodings[0])
                known_face_names.append(name)
                print(f"Loaded face: {name}")
            else:
                print(f"No face found in {image_file}")

    return known_face_encodings, known_face_names


def process_frame(frame, known_face_encodings, known_face_names):
    small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
    rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)

    face_locations = face_recognition.face_locations(rgb_small_frame, model="hog")
    face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

    face_names = []
    for face_encoding in face_encodings:
        matches = face_recognition.compare_faces(known_face_encodings, face_encoding, tolerance=0.6)
        name = "Unknown"

        if True in matches:
            first_match_index = matches.index(True)
            name = known_face_names[first_match_index]

        face_names.append(name)

    return face_locations, face_names


def main():
    print("Loading known faces...")
    known_face_encodings, known_face_names = load_known_faces("images")

    if not known_face_encodings:
        print("No faces loaded from images folder!")
        return

    print("Starting webcam...")
    cap = cv2.VideoCapture(0)
    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
    cap.set(cv2.CAP_PROP_FPS, 30)

    if not cap.isOpened():
        print("Could not open webcam!")
        return

    print("Face recognition started. Press 'q' to quit.")
    face_locations = []
    face_names = []
    frame_count = 0

    frame_queue = Queue(maxsize=1)
    stop_thread = False

    def capture_frames():
        nonlocal stop_thread
        while not stop_thread:
            ret, frame = cap.read()
            if ret:
                if not frame_queue.full():
                    frame_queue.put(frame)

    capture_thread = threading.Thread(target=capture_frames)
    capture_thread.start()

    while True:
        if not frame_queue.empty():
            frame = frame_queue.get()

            if frame_count % 3 == 0:
                face_locations, face_names = process_frame(frame, known_face_encodings, known_face_names)

            for (top, right, bottom, left), name in zip(face_locations, face_names):
                top *= 4
                right *= 4
                bottom *= 4
                left *= 4

                cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
                cv2.rectangle(frame, (left, bottom - 35), (right, bottom), (0, 255, 0), cv2.FILLED)
                cv2.putText(frame, name, (left + 6, bottom - 6),
                            cv2.FONT_HERSHEY_DUPLEX, 0.6, (255, 255, 255), 1)

            cv2.imshow('Face Recognition', frame)
            frame_count += 10

        if cv2.waitKey(1) & 0xFF == ord('q'):
            stop_thread = True
            break

    capture_thread.join()
    cap.release()
    cv2.destroyAllWindows()


if __name__ == "__main__":
    main()
