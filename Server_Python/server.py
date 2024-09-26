# server.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import face_recognition
import numpy as np
import os
import base64
import cv2  # Import OpenCV

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

@app.route('/register', methods=['POST'])
def register():
    data = request.json
    image_data = base64.b64decode(data['image'])
    name = data['name']

    # Convert image data to numpy array
    image = np.frombuffer(image_data, dtype=np.uint8)
    image = cv2.imdecode(image, cv2.IMREAD_COLOR)

    face_locations = face_recognition.face_locations(image)
    face_encodings = face_recognition.face_encodings(image, face_locations)

    if face_encodings:
        encoding = face_encodings[0]
        if not os.path.exists('faces'):
            os.makedirs('faces')
        with open(f'faces/{ name}.txt', 'w') as f:
            f.write(str(encoding.tolist()))
        return jsonify({ 'status': 'success', 'message': f'Face registered for {name}'}), 200
    else:
        return jsonify({ 'status': 'failure', 'message': 'No face detected'}), 400
        
if __name__ == '__main__':
    app.run(debug=True)
