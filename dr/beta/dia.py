import joblib
import pandas as pd
import numpy as np
import os
from xgboost import XGBClassifier

# Function to train and save the diabetes model
def train_and_save_model():
    # Sample training data (replace with actual data)
    X_train = pd.DataFrame({
        'Glucose': [0.1, 0.2, 0.3],
        'Cholesterol': [0.2, 0.1, 0.3],
        'Hemoglobin': [0.9, 0.8, 0.7],
        'Platelets': [0.6, 0.5, 0.4],
        'White Blood Cells': [0.15, 0.16, 0.17],
        'Red Blood Cells': [0.18, 0.19, 0.20],
        'Hematocrit': [0.21, 0.22, 0.23],
        'Mean Corpuscular Volume': [0.24, 0.25, 0.26],
        'Mean Corpuscular Hemoglobin': [0.27, 0.28, 0.29],
        'Mean Corpuscular Hemoglobin Concentration': [0.30, 0.31, 0.32],
        'Insulin': [0.5, 0.6, 0.4],
        'BMI': [0.8, 0.7, 0.6],
        'Systolic Blood Pressure': [0.5, 0.6, 0.7],
        'Diastolic Blood Pressure': [0.4, 0.5, 0.6],
        'Triglycerides': [0.3, 0.2, 0.1],
        'HbA1c': [0.4, 0.5, 0.6],
        'LDL Cholesterol': [0.7, 0.8, 0.9],
        'HDL Cholesterol': [0.1, 0.2, 0.3],
        'ALT': [0.4, 0.5, 0.6],
        'AST': [0.7, 0.8, 0.9],
        'Heart Rate': [0.6, 0.7, 0.8],
        'Creatinine': [0.9, 0.8, 0.7],
        'Troponin': [0.1, 0.2, 0.3],
        'C-reactive Protein': [0.4, 0.5, 0.6],
    })
    y_train = [1, 0, 1]  # Sample target variable (1: diabetic, 0: non-diabetic)

    # Train the model
    model = XGBClassifier()
    model.fit(X_train, y_train)

    # Save the model
    joblib.dump(model, 'C:\\Users\\11\\Desktop\\work\\dr\\diabetes.pkl')
    print("Model trained and saved successfully!")

# Function to get default diabetes data
def get_default_diabetes_data():
    return {
        'Glucose': 0.121786313,
        'Cholesterol': 0.023058437,
        'Hemoglobin': 0.94489324,
        'Platelets': 0.905372145,
        'White Blood Cells': 0.507710985,
        'Red Blood Cells': 0.40303319,
        'Hematocrit': 0.164216445,
        'Mean Corpuscular Volume': 0.307553206,
        'Mean Corpuscular Hemoglobin': 0.207938382,
        'Mean Corpuscular Hemoglobin Concentration': 0.505561858,
        'Insulin': 0.571161502,
        'BMI': 0.839270508,
        'Systolic Blood Pressure': 0.580902575,
        'Diastolic Blood Pressure': 0.556037486,
        'Triglycerides': 0.47774212,
        'HbA1c': 0.856809908,
        'LDL Cholesterol': 0.652465332,
        'HDL Cholesterol': 0.106960917,
        'ALT': 0.94254879,
        'AST': 0.344260902,
        'Heart Rate': 0.66636811,
        'Creatinine': 0.659059785,
        'Troponin': 0.816982046,
        'C-reactive Protein': 0.401165962
    }

# Function to test the diabetes model
def test_diabetes_model():
    try:
        model_path = "C:\\Users\\11\\Desktop\\work\\dr\\diabetes.pkl"
        print(f"\nChecking model file:")
        print(f"Path: {model_path}")
        print(f"File exists: {os.path.exists(model_path)}")

        print("\nLoading model...")
        loaded_model = joblib.load(model_path)
        print("Model loaded successfully!")

        print("\nCreating test data...")
        default_data = get_default_diabetes_data()
        test_data = pd.DataFrame([default_data])

        print("\nTest data shape:", test_data.shape)
        print("Test data columns:", test_data.columns.tolist())

        # Making prediction
        print("\nMaking prediction...")
        prediction = loaded_model.predict(test_data)
        prediction_proba = loaded_model.predict_proba(test_data)

        print("\n=== Results ===")
        print(f"Prediction: {prediction[0]}")
        print(f"Probability: {prediction_proba[0]}")

        if prediction[0] == 1:
            print("\n🔍 **الشخص يعاني من السكري.**")
            print(f"نسبة الاحتمالية: {prediction_proba[0][1] * 100:.2f}%")
        else:
            print("\n🔍 **الشخص لا يعاني من السكري.**")
            print(f"نسبة الاحتمالية: {prediction_proba[0][0] * 100:.2f}%")

        return True

    except Exception as e:
        print(f"\n❌ Error occurred: {str(e)}")
        return False

if __name__ == "__main__":
    print("=== Starting Diabetes Model Test ===")
    train_and_save_model()  # Uncomment this line to train and save the model
    success = test_diabetes_model()
    if success:
        print("\n✅ Test completed successfully!")
    else:
        print("\n❌ Test failed!")