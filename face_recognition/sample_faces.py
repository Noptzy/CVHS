import cv2
import face_recognition
import os
from pathlib import Path


def load_known_faces(images_folder):
    known_face_encodings = []
    known_face_names = []

    # Scan folder images
    images_path = Path(images_folder)
    valid_extensions = ['.jpg', '.jpeg', '.png']

    for image_file in images_path.iterdir():
        if image_file.suffix.lower() in valid_extensions:
            # Get name from filename without extension
            name = image_file.stem

            # Load and encode face
            image = face_recognition.load_image_file(str(image_file))
            face_encodings = face_recognition.face_encodings(image)

            if face_encodings:
                known_face_encodings.append(face_encodings[0])
                known_face_names.append(name)
                print(f"Loaded face: {name}")
            else:
                print(f"No face found in {image_file}")

    return known_face_encodings, known_face_names


def main():
    # Load known faces
    print("Loading known faces...")
    known_face_encodings, known_face_names = load_known_faces("images")

    if not known_face_encodings:
        print("No faces loaded from images folder!")
        return

    # Initialize webcam
    print("Starting webcam...")
    cap = cv2.VideoCapture(0)

    if not cap.isOpened():
        print("Could not open webcam!")
        return

    print("Face recognition started. Press 'q' to quit.")

    while True:
        # Capture frame
        ret, frame = cap.read()
        if not ret:
            print("Failed to capture frame!")
            break

        # Resize frame for faster processing
        small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)

        # Convert BGR to RGB
        rgb_small_frame = small_frame[:, :, ::-1]

        # Find faces in current frame
        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

        # Process each face
        for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
            # Scale back face locations
            top *= 4
            right *= 4
            bottom *= 4
            left *= 4

            # Check if face matches any known faces
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            name = "Unknown"

            if True in matches:
                first_match_index = matches.index(True)
                name = known_face_names[first_match_index]

            # Draw box and name
            cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)

            # Add name label
            cv2.rectangle(frame, (left, bottom - 35), (right, bottom), (0, 255, 0), cv2.FILLED)
            font = cv2.FONT_HERSHEY_DUPLEX
            cv2.putText(frame, name, (left + 6, bottom - 6), font, 0.6, (255, 255, 255), 1)

        # Display frame
        cv2.imshow('Face Recognition', frame)

        # Break loop if 'q' is pressed
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    # Cleanup
    cap.release()
    cv2.destroyAllWindows()


if __name__ == "__main__":
    main()