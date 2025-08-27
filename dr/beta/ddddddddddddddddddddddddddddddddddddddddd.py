import customtkinter as ctk
import tkinter as tk
from tkinter import messagebox
import time
import math
from PIL import Image, ImageTk
import pandas as pd
import joblib
import os
import pickle
import numpy as np

class NeonHealthApp:
    def __init__(self):
        self.root = tk.Tk()
        self.root.title("VitaGuard AI")
        self.root.geometry("800x600")
        
        # Load models
        self.heart_model = None
        self.kidney_model = None
        self.liver_model = None
        self.anemia_model = None
        self.diabetes_model = None
        
        # Load images
        try:
            self.pancreas_image = Image.open("assets/3.png")
            self.pancreas_image = self.pancreas_image.resize((300, 300), Image.Resampling.LANCZOS)
            self.pancreas_photo = ImageTk.PhotoImage(self.pancreas_image)
        except Exception as e:
            print(f"Error loading pancreas image: {e}")
            self.pancreas_photo = None
        
        self.load_and_verify_models()
        self.show_login()

    def load_and_verify_models(self):
        """Load and verify all prediction models"""
        try:
            # Load models using full path
            model_dir = "C:\\Users\\11\\Desktop\\work\\dr"
            
            # Load heart model
            heart_model_path = os.path.join(model_dir, 'heart.pkl')
            if os.path.exists(heart_model_path):
                self.heart_model = joblib.load(heart_model_path)
                print("Heart model loaded successfully")
            else:
                print("Heart model file not found")
                self.heart_model = None

            # Load liver model
            liver_model_path = os.path.join(model_dir, 'liver.pkl')
            if os.path.exists(liver_model_path):
                self.liver_model = joblib.load(liver_model_path)
                print("Liver model loaded successfully")
            else:
                print(f"Liver model file not found at {liver_model_path}")
                self.liver_model = None

            # Load kidney model
            kidney_model_path = os.path.join(model_dir, 'kidney.pkl')
            if os.path.exists(kidney_model_path):
                self.kidney_model = joblib.load(kidney_model_path)
                print("Kidney model loaded successfully")
            else:
                print("Kidney model file not found")
                self.kidney_model = None

            # Load anemia model
            anemia_model_path = os.path.join(model_dir, 'anemia.pkl')
            if os.path.exists(anemia_model_path):
                self.anemia_model = joblib.load(anemia_model_path)
                print("Anemia model loaded successfully")
            else:
                print("Anemia model file not found")
                self.anemia_model = None

            # Load diabetes model
            diabetes_model_path = os.path.join(model_dir, 'diabetes.pkl')
            if os.path.exists(diabetes_model_path):
                self.diabetes_model = joblib.load(diabetes_model_path)
                print("Diabetes model loaded successfully")
            else:
                print("Diabetes model file not found")
                self.diabetes_model = None

            # Verify models with test data
            self.verify_models()
            
        except Exception as e:
            print(f"Error loading models: {str(e)}")
            messagebox.showerror("Error", f"Failed to load models: {str(e)}")

    def verify_models(self):
        """Verify prediction models with test data"""
        print("\nStarting model verification...")
        
        model_dir = r"C:\Users\11\Desktop\work\dr"
        
        # Load diabetes model
        try:
            print("\nLoading diabetes model...")
            model_path = os.path.join(model_dir, 'diabetes.pkl')
            print(f"Model path: {model_path}")
            if os.path.exists(model_path):
                self.diabetes_model = joblib.load(model_path)
                print("Diabetes model loaded successfully")
                
                # Create test data with all 24 features
                test_data = pd.DataFrame([{
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
                }])
                
                print("Test data shape:", test_data.shape)
                print("Test data columns:", test_data.columns.tolist())
                
                try:
                    prediction = self.diabetes_model.predict(test_data)
                    prediction_proba = self.diabetes_model.predict_proba(test_data)
                    print("Diabetes model verification successful")
                except Exception as e:
                    print(f"Diabetes model verification failed: {str(e)}")
                    self.diabetes_model = None
            else:
                print(f"Diabetes model file not found at {model_path}")
                self.diabetes_model = None
                
        except Exception as e:
            print(f"Error loading diabetes model: {str(e)}")
            self.diabetes_model = None
            messagebox.showerror("Model Error", 
                "Failed to load the diabetes prediction model. Some features may be unavailable.")

        # Load heart model
        try:
            model_path = os.path.join(model_dir, 'heart.pkl')
            if os.path.exists(model_path):
                self.heart_model = joblib.load(model_path)
                print("Heart model loaded successfully")
            else:
                print(f"Heart model file not found at {model_path}")
                self.heart_model = None
                
        except Exception as e:
            print(f"Error loading heart disease model: {str(e)}")
            self.heart_model = None
            messagebox.showerror("Model Error", 
                "Failed to load the heart disease prediction model. Some features may be unavailable.")

        # Load liver model
        try:
            model_path = os.path.join(model_dir, 'liver.pkl')
            if os.path.exists(model_path):
                self.liver_model = joblib.load(model_path)
                print("Liver model loaded successfully")
            else:
                print(f"Liver model file not found at {model_path}")
                self.liver_model = None
                
        except Exception as e:
            print(f"Error loading liver disease model: {str(e)}")
            self.liver_model = None
            messagebox.showerror("Model Error", 
                "Failed to load the liver disease prediction model. Some features may be unavailable.")

        # Load kidney model
        try:
            model_path = os.path.join(model_dir, 'kidney.pkl')
            if os.path.exists(model_path):
                self.kidney_model = joblib.load(model_path)
                print("Kidney model loaded successfully")
            else:
                print(f"Kidney model file not found at {model_path}")
                self.kidney_model = None
                
        except Exception as e:
            print(f"Error loading kidney disease model: {str(e)}")
            self.kidney_model = None
            messagebox.showerror("Model Error", 
                "Failed to load the kidney disease prediction model. Some features may be unavailable.")

        # Load anemia model
        try:
            model_path = os.path.join(model_dir, 'anemia.pkl')
            if os.path.exists(model_path):
                self.anemia_model = joblib.load(model_path)
                print("Anemia model loaded successfully")
            else:
                print(f"Anemia model file not found at {model_path}")
                self.anemia_model = None
                
        except Exception as e:
            print(f"Error loading anemia model: {str(e)}")
            self.anemia_model = None
            messagebox.showerror("Model Error", 
                "Failed to load the anemia prediction model. Some features may be unavailable.")

    def process_heart_data(self, data):
        """Process and validate heart disease prediction data"""
        try:
            # Create DataFrame
            df = pd.DataFrame([data])
            
            # Verify all required columns are present
            required_columns = [
                'male', 'age', 'currentSmoker', 'cigsPerDay', 'BPMeds',
                'prevalentStroke', 'prevalentHyp', 'diabetes', 'totChol',
                'sysBP', 'diaBP', 'BMI', 'heartRate', 'glucose'
            ]
            
            for col in required_columns:
                if col not in df.columns:
                    raise ValueError(f"Missing required parameter: {col}")
            
            # Ensure correct data types and ranges
            validations = {
                'age': (0, 120),
                'cigsPerDay': (0, 100),
                'totChol': (100, 600),
                'sysBP': (80, 300),
                'diaBP': (40, 200),
                'BMI': (10, 100),
                'heartRate': (40, 220),
                'glucose': (40, 400)
            }
            
            for col, (min_val, max_val) in validations.items():
                df[col] = pd.to_numeric(df[col], errors='raise')
                if df[col].iloc[0] < min_val or df[col].iloc[0] > max_val:
                    raise ValueError(f"{col} must be between {min_val} and {max_val}")
            
            # Convert boolean columns to 0/1
            boolean_columns = ['male', 'currentSmoker', 'BPMeds', 
                             'prevalentStroke', 'prevalentHyp', 'diabetes']
            for col in boolean_columns:
                df[col] = df[col].astype(int)
            
            # Ensure DataFrame has correct column order
            df = df[required_columns]
            
            return df
            
        except Exception as e:
            raise ValueError(f"Data processing error: {str(e)}")

    def process_kidney_data(self, data):
        """Process and validate kidney disease prediction data"""
        try:
            # Create DataFrame with a single row
            df = pd.DataFrame([data])
            
            # Verify all required columns are present
            required_columns = [
                "Age", "Gender", "BMI", "Smoking", "AlcoholConsumption", 
                "FamilyHistoryKidneyDisease", "FamilyHistoryHypertension", 
                "FamilyHistoryDiabetes", "PreviousAcuteKidneyInjury",
                "SystolicBP", "DiastolicBP", "FastingBloodSugar", "HbA1c", 
                "SerumCreatinine", "BUNLevels", "GFR", "ProteinInUrine", "ACR",
                "SerumElectrolytesSodium", "SerumElectrolytesPotassium",
                "SerumElectrolytesCalcium", "SerumElectrolytesPhosphorus",
                "HemoglobinLevels", "Diuretics", "Edema", "FatigueLevels",
                "NauseaVomiting", "MuscleCramps", "Itching"
            ]
            
            for col in required_columns:
                if col not in df.columns:
                    raise ValueError(f"Missing required parameter: {col}")
            
            # Ensure correct data types and ranges
            validations = {
                'Age': (0, 120),                          # Age in years
                'Gender': (0, 1),                         # 0 for Female, 1 for Male
                'BMI': (10, 100),                         # BMI range
                'Smoking': (0, 1),                        # 0 for No, 1 for Yes
                'AlcoholConsumption': (0, 100),           # Units per week
                'FamilyHistoryKidneyDisease': (0, 1),     # 0 for No, 1 for Yes
                'FamilyHistoryHypertension': (0, 1),      # 0 for No, 1 for Yes
                'FamilyHistoryDiabetes': (0, 1),          # 0 for No, 1 for Yes
                'PreviousAcuteKidneyInjury': (0, 1),      # 0 for No, 1 for Yes
                'SystolicBP': (70, 250),                  # Systolic BP in mmHg
                'DiastolicBP': (40, 150),                 # Diastolic BP in mmHg
                'FastingBloodSugar': (30, 500),          # FBS in mg/dL
                'HbA1c': (3, 15),                        # HbA1c percentage
                'SerumCreatinine': (0.1, 15),            # Creatinine in mg/dL
                'BUNLevels': (2, 200),                   # BUN in mg/dL
                'GFR': (0, 200),                         # GFR in mL/min/1.73m²
                'ProteinInUrine': (0, 1000),             # Protein in mg/dL
                'ACR': (0, 1000),                        # ACR in mg/g
                'SerumElectrolytesSodium': (110, 180),   # Sodium in mEq/L
                'SerumElectrolytesPotassium': (2, 8),    # Potassium in mEq/L
                'SerumElectrolytesCalcium': (2, 15),     # Calcium in mg/dL
                'SerumElectrolytesPhosphorus': (1, 10),  # Phosphorus in mg/dL
                'HemoglobinLevels': (3, 20),             # Hemoglobin in g/dL
                'Diuretics': (0, 1),                     # 0 for No, 1 for Yes
                'Edema': (0, 1),                         # 0 for No, 1 for Yes
                'FatigueLevels': (1, 10),                # Fatigue scale 1-10
                'NauseaVomiting': (0, 1),                # 0 for No, 1 for Yes
                'MuscleCramps': (0, 1),                  # 0 for No, 1 for Yes
                'Itching': (0, 1)                        # 0 for No, 1 for Yes
            }
            
            # Convert categorical variables
            categorical_vars = ['Gender', 'Smoking', 'FamilyHistoryKidneyDisease', 
                              'FamilyHistoryHypertension', 'FamilyHistoryDiabetes',
                              'PreviousAcuteKidneyInjury', 'Diuretics', 'Edema',
                              'NauseaVomiting', 'MuscleCramps', 'Itching']
            
            for col in categorical_vars:
                if isinstance(df[col].iloc[0], str):
                    if col == 'Gender':
                        df[col] = df[col].map({'Male': 1, 'Female': 0})
                    else:
                        df[col] = df[col].map({'Yes': 1, 'No': 0})
            
            # Validate numeric fields
            for col, (min_val, max_val) in validations.items():
                try:
                    df[col] = pd.to_numeric(df[col], errors='raise')
                    if df[col].iloc[0] < min_val or df[col].iloc[0] > max_val:
                        raise ValueError(f"{col} must be between {min_val} and {max_val}")
                except ValueError as e:
                    if "could not convert string to float" in str(e):
                        raise ValueError(f"Please enter a valid number for {col}")
                    raise e
            
            # Ensure DataFrame has correct column order
            df = df[required_columns]
            
            print("Processed kidney data shape:", df.shape)
            print("Processed kidney data columns:", df.columns.tolist())
            print("Processed kidney data sample:", df.iloc[0].to_dict())
            
            return df
            
        except Exception as e:
            raise ValueError(f"Data processing error: {str(e)}")

    def process_liver_data(self, data):
        """Process and validate liver disease prediction data"""
        try:
            # Create DataFrame
            df = pd.DataFrame([data])
            
            # Map the input column names to the model's expected column names
            column_mapping = {
                'age': 'Age of the patient',
                'gender': 'Gender of the patient',
                'total_bilirubin': 'Total Bilirubin',
                'direct_bilirubin': 'Direct Bilirubin',
                'alkphos': 'Alkphos Alkaline Phosphotase',
                'sgpt': 'Sgpt Alamine Aminotransferase',
                'sgot': 'Sgot Aspartate Aminotransferase',
                'total_proteins': 'Total Protiens',
                'albumin': 'ALB Albumin',
                'ag_ratio': 'A/G Ratio Albumin and Globulin Ratio'
            }
            
            # Verify all required columns are present
            required_columns = list(column_mapping.keys())
            
            for col in required_columns:
                if col not in df.columns:
                    raise ValueError(f"Missing required parameter: {col}")
            
            # Ensure correct data types and ranges
            validations = {
                'age': (0, 120),                    # Age in years
                'gender': (0, 1),                   # Gender (0 or 1)
                'total_bilirubin': (0.1, 30),       # Total Bilirubin in mg/dL
                'direct_bilirubin': (0.1, 15),      # Direct Bilirubin in mg/dL
                'alkphos': (20, 1000),              # Alkaline Phosphotase in IU/L
                'sgpt': (1, 1000),                  # SGPT in IU/L
                'sgot': (1, 1000),                  # SGOT in IU/L
                'total_proteins': (1, 15),          # Total Proteins in g/dL
                'albumin': (1, 8),                  # Albumin in g/dL
                'ag_ratio': (0.1, 5)                # Albumin/Globulin Ratio
            }
            
            # Convert gender to numeric
            if isinstance(data['gender'], str):
                if data['gender'].lower() in ['male', '1', 'true']:
                    df['gender'] = 1
                elif data['gender'].lower() in ['female', '0', 'false']:
                    df['gender'] = 0
                else:
                    raise ValueError("Gender must be 'Male' or 'Female'")
            
            # Validate numeric fields
            for col, (min_val, max_val) in validations.items():
                if col != 'gender':  # Skip gender as it's handled above
                    try:
                        df[col] = pd.to_numeric(df[col], errors='raise')
                        if df[col].iloc[0] < min_val or df[col].iloc[0] > max_val:
                            raise ValueError(f"{col} must be between {min_val} and {max_val}")
                    except ValueError as e:
                        if "could not convert string to float" in str(e):
                            raise ValueError(f"Please enter a valid number for {col}")
                        raise e
            
            # Rename columns to match model's expected names
            df = df.rename(columns=column_mapping)
            
            # Add data type verification
            print("Processed DataFrame for Liver Model:")
            print(df.dtypes)  # Check all values are numeric
            print(df)  # Display data before prediction
            
            return df
            
        except Exception as e:
            raise ValueError(f"Data processing error: {str(e)}")

    def process_anemia_data(self, data):
        """Process and validate anemia test data"""
        try:
            # Create DataFrame
            df = pd.DataFrame([data])
            
            # Verify all required columns are present
            required_columns = [
                'WBC', 'LYMp', 'NEUTp', 'LYMn', 'NEUTn', 'RBC', 'HGB', 'HCT',
                'MCV', 'MCH', 'MCHC', 'PLT', 'PDW', 'PCT'
            ]
            
            for col in required_columns:
                if col not in df.columns:
                    raise ValueError(f"Missing required parameter: {col}")
            
            # Validate numeric fields - only check if they can be converted to numbers
            for col in required_columns:
                try:
                    df[col] = pd.to_numeric(df[col], errors='raise')
                except ValueError as e:
                    if "could not convert string to float" in str(e):
                        raise ValueError(f"Please enter a valid number for {col}")
                    raise e
            
            # Ensure DataFrame has correct column order
            df = df[required_columns]
            
            return df
            
        except Exception as e:
            raise ValueError(f"Data processing error: {str(e)}")

    def process_diabetes_data(self, data):
        """Process diabetes test data and make prediction"""
        if self.diabetes_model is None:
            print("Error: Diabetes model is not loaded")
            raise ValueError("Diabetes prediction model is not available")
        
        print("Processing diabetes data...")
        
        # Create DataFrame with all required parameters
        test_data = pd.DataFrame([{
            'Glucose': float(data.get('Glucose', 0)),
            'Cholesterol': float(data.get('Cholesterol', 0)),
            'Hemoglobin': float(data.get('Hemoglobin', 0)),
            'Platelets': float(data.get('Platelets', 0)),
            'White Blood Cells': float(data.get('White Blood Cells', 0)),
            'Red Blood Cells': float(data.get('Red Blood Cells', 0)),
            'Hematocrit': float(data.get('Hematocrit', 0)),
            'Mean Corpuscular Volume': float(data.get('Mean Corpuscular Volume', 0)),
            'Mean Corpuscular Hemoglobin': float(data.get('Mean Corpuscular Hemoglobin', 0)),
            'Mean Corpuscular Hemoglobin Concentration': float(data.get('Mean Corpuscular Hemoglobin Concentration', 0)),
            'Insulin': float(data.get('Insulin', 0)),
            'BMI': float(data.get('BMI', 0)),
            'Systolic Blood Pressure': float(data.get('Systolic Blood Pressure', 0)),
            'Diastolic Blood Pressure': float(data.get('Diastolic Blood Pressure', 0)),
            'Triglycerides': float(data.get('Triglycerides', 0)),
            'HbA1c': float(data.get('HbA1c', 0)),
            'LDL Cholesterol': float(data.get('LDL Cholesterol', 0)),
            'HDL Cholesterol': float(data.get('HDL Cholesterol', 0)),
            'ALT': float(data.get('ALT', 0)),
            'AST': float(data.get('AST', 0)),
            'Heart Rate': float(data.get('Heart Rate', 0)),
            'Creatinine': float(data.get('Creatinine', 0)),
            'Troponin': float(data.get('Troponin', 0)),
            'C-reactive Protein': float(data.get('C-reactive Protein', 0))
        }])
        
        print("Input data shape:", test_data.shape)
        print("Input columns:", test_data.columns.tolist())
        
        # Make prediction
        print("Making prediction...")
        prediction = self.diabetes_model.predict(test_data)
        prediction_proba = self.diabetes_model.predict_proba(test_data)
        
        # Calculate risk factors
        risk_factors = []
        try:
            if float(data.get('Glucose', 0)) > 0.6: risk_factors.append("High Blood Glucose")
            if float(data.get('HbA1c', 0)) > 0.6: risk_factors.append("High Glycated Hemoglobin")
            if float(data.get('Insulin', 0)) > 0.6: risk_factors.append("Insulin Resistance")
            if float(data.get('BMI', 0)) > 0.7: risk_factors.append("High Body Mass Index")
            if float(data.get('Systolic Blood Pressure', 0)) > 0.6: risk_factors.append("High Blood Pressure")
            if float(data.get('Triglycerides', 0)) > 0.6: risk_factors.append("High Triglycerides")
            if float(data.get('LDL Cholesterol', 0)) > 0.6: risk_factors.append("High LDL Cholesterol")
            if float(data.get('HDL Cholesterol', 0)) < 0.4: risk_factors.append("Low HDL Cholesterol")
            if float(data.get('ALT', 0)) > 0.6: risk_factors.append("Elevated Liver Enzymes")
            if float(data.get('Creatinine', 0)) > 0.6: risk_factors.append("High Creatinine")
            if float(data.get('C-reactive Protein', 0)) > 0.6: risk_factors.append("High C-reactive Protein")
        except Exception as e:
            print(f"Error calculating risk factors: {str(e)}")
        
        # Store results
        self.prediction_results = {
            "prediction": int(prediction[0]),
            "result_text": self.get_result_text(int(prediction[0]), "DIABETES SCAN"),
            "probability": prediction_proba[0][1],
            "input_data": data,
            "risk_factors": risk_factors
        }
        
        return True

    def get_result_text(self, prediction, test_type):
        """Convert numeric prediction to text result"""
        if test_type == "HEART SCAN":
            return "Positive - Cardiac Risk Detected" if prediction == 1 else "Negative - Heart is Healthy"
        elif test_type == "KIDNEY SCAN":
            return "Positive - Kidney Issues Detected" if prediction == 1 else "Negative - Kidneys are Healthy"
        elif test_type == "LIVER SCAN":
            return "Positive - Liver Issues Detected" if prediction == 1 else "Negative - Liver is Healthy"
        elif test_type == "ANEMIA SCAN":
            return "Positive - Anemia Detected" if prediction == 1 else "Negative - No Anemia Detected"
        elif test_type == "DIABETES SCAN":
            return "Positive - Diabetes Risk Detected" if prediction == 1 else "Negative - Normal Blood Sugar Levels"
        return "Unknown"

    def validate_and_analyze(self, test_type, data):
        """Validate inputs and perform analysis"""
        try:
            print(f"Starting analysis for {test_type}")
            print(f"Input data: {data}")
            
            if test_type == "QUANTUM KIDNEY ANALYSIS":
                if self.kidney_model is None:
                    print("Error: Kidney model is not loaded")
                    raise ValueError("Kidney disease prediction model is not available")
                
                print("Processing kidney data...")
                # Process and validate data
                df = self.process_kidney_data(data)
                print(f"Processed DataFrame: {df}")
                
                # Apply preprocessing if model has preprocessor
                if hasattr(self.kidney_model, 'named_steps') and 'preprocessor' in self.kidney_model.named_steps:
                    print("Applying preprocessing...")
                    preprocessed_data = self.kidney_model.named_steps["preprocessor"].transform(df)
                    print("Data preprocessed successfully")
                    
                    # Make prediction using classifier
                    print("Making prediction...")
                    prediction = self.kidney_model.named_steps["classifier"].predict(preprocessed_data)
                    prediction_proba = self.kidney_model.named_steps["classifier"].predict_proba(preprocessed_data)
                else:
                    # Make prediction directly if no preprocessor
                    print("Making prediction without preprocessing...")
                    prediction = self.kidney_model.predict(df)
                    prediction_proba = self.kidney_model.predict_proba(df)
                
                print(f"Prediction: {prediction}")
                print(f"Prediction probability: {prediction_proba}")
                
                # Calculate risk factors
                print("Calculating risk factors...")
                risk_factors = []
                
                try:
                    # Demographics and Lifestyle
                    if float(data['Age']) > 60: risk_factors.append("Advanced age")
                    if float(data['BMI']) > 30: risk_factors.append("Obesity")
                    if data['Smoking'] == 'Yes': risk_factors.append("Current smoker")
                    if float(data['AlcoholConsumption']) > 14: risk_factors.append("High alcohol consumption")
                    
                    # Medical History
                    if data['FamilyHistoryKidneyDisease'] == 'Yes': risk_factors.append("Family history of kidney disease")
                    if data['FamilyHistoryHypertension'] == 'Yes': risk_factors.append("Family history of hypertension")
                    if data['FamilyHistoryDiabetes'] == 'Yes': risk_factors.append("Family history of diabetes")
                    if data['PreviousAcuteKidneyInjury'] == 'Yes': risk_factors.append("History of acute kidney injury")
                    
                    # Vital Signs
                    if float(data['SystolicBP']) > 140: risk_factors.append("High systolic blood pressure")
                    if float(data['DiastolicBP']) > 90: risk_factors.append("High diastolic blood pressure")
                    
                    # Blood Tests
                    if float(data['FastingBloodSugar']) > 126: risk_factors.append("High fasting blood sugar")
                    if float(data['HbA1c']) > 6.5: risk_factors.append("Elevated HbA1c")
                    if float(data['SerumCreatinine']) > 1.2: risk_factors.append("Elevated serum creatinine")
                    if float(data['BUNLevels']) > 20: risk_factors.append("Elevated BUN levels")
                    if float(data['GFR']) < 60: risk_factors.append("Reduced GFR")
                    
                    # Urine Tests
                    if float(data['ProteinInUrine']) > 0.3: risk_factors.append("Proteinuria")
                    if float(data['ACR']) > 30: risk_factors.append("Elevated ACR")
                    
                    # Electrolytes
                    if float(data['SerumElectrolytesSodium']) < 135 or float(data['SerumElectrolytesSodium']) > 145:
                        risk_factors.append("Abnormal sodium levels")
                    if float(data['SerumElectrolytesPotassium']) < 3.5 or float(data['SerumElectrolytesPotassium']) > 5.0:
                        risk_factors.append("Abnormal potassium levels")
                    if float(data['SerumElectrolytesCalcium']) < 8.5 or float(data['SerumElectrolytesCalcium']) > 10.5:
                        risk_factors.append("Abnormal calcium levels")
                    if float(data['SerumElectrolytesPhosphorus']) > 4.5:
                        risk_factors.append("Elevated phosphorus")
                    
                    # Additional Tests
                    if float(data['HemoglobinLevels']) < 12: risk_factors.append("Anemia")
                    
                    # Symptoms
                    if data['Diuretics'] == 'Yes': risk_factors.append("Using diuretics")
                    if data['Edema'] == 'Yes': risk_factors.append("Presence of edema")
                    if float(data['FatigueLevels']) > 7: risk_factors.append("Severe fatigue")
                    if data['NauseaVomiting'] == 'Yes': risk_factors.append("Nausea/vomiting")
                    if data['MuscleCramps'] == 'Yes': risk_factors.append("Muscle cramps")
                    if data['Itching'] == 'Yes': risk_factors.append("Uremic pruritus")
                    
                except Exception as e:
                    print(f"Error calculating risk factors: {str(e)}")
                    
                print(f"Identified risk factors: {risk_factors}")
                
                # Store results
                print("Storing prediction results...")
                self.prediction_results = {
                    "prediction": int(prediction[0]),
                    "result_text": self.get_result_text(int(prediction[0]), "KIDNEY SCAN"),
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                print(f"Stored prediction results: {self.prediction_results}")
                
                return True
            
            elif test_type == "NEURAL DIABETES SCAN":
                if self.diabetes_model is None:
                    print("Error: Diabetes model is not loaded")
                    raise ValueError("Diabetes prediction model is not available")
                
                print("Processing diabetes data...")
                # Create DataFrame with all required parameters
                test_data = pd.DataFrame([{
                    'Glucose': float(data.get('Glucose', 0)),
                    'Cholesterol': float(data.get('Cholesterol', 0)),
                    'Hemoglobin': float(data.get('Hemoglobin', 0)),
                    'Platelets': float(data.get('Platelets', 0)),
                    'White Blood Cells': float(data.get('White Blood Cells', 0)),
                    'Red Blood Cells': float(data.get('Red Blood Cells', 0)),
                    'Hematocrit': float(data.get('Hematocrit', 0)),
                    'Mean Corpuscular Volume': float(data.get('Mean Corpuscular Volume', 0)),
                    'Mean Corpuscular Hemoglobin': float(data.get('Mean Corpuscular Hemoglobin', 0)),
                    'Mean Corpuscular Hemoglobin Concentration': float(data.get('Mean Corpuscular Hemoglobin Concentration', 0)),
                    'Insulin': float(data.get('Insulin', 0)),
                    'BMI': float(data.get('BMI', 0)),
                    'Systolic Blood Pressure': float(data.get('Systolic Blood Pressure', 0)),
                    'Diastolic Blood Pressure': float(data.get('Diastolic Blood Pressure', 0)),
                    'Triglycerides': float(data.get('Triglycerides', 0)),
                    'HbA1c': float(data.get('HbA1c', 0)),
                    'LDL Cholesterol': float(data.get('LDL Cholesterol', 0)),
                    'HDL Cholesterol': float(data.get('HDL Cholesterol', 0)),
                    'ALT': float(data.get('ALT', 0)),
                    'AST': float(data.get('AST', 0)),
                    'Heart Rate': float(data.get('Heart Rate', 0)),
                    'Creatinine': float(data.get('Creatinine', 0)),
                    'Troponin': float(data.get('Troponin', 0)),
                    'C-reactive Protein': float(data.get('C-reactive Protein', 0))
                }])
                
                # Make prediction
                print("Making prediction...")
                prediction = self.diabetes_model.predict(test_data)
                prediction_proba = self.diabetes_model.predict_proba(test_data)
                
                # Calculate risk factors
                risk_factors = []
                try:
                    if float(data.get('Glucose', 0)) > 0.6: risk_factors.append("ارتفاع مستوى السكر في الدم")
                    if float(data.get('HbA1c', 0)) > 0.6: risk_factors.append("ارتفاع مستوى الهيموجلوبين السكري")
                    if float(data.get('Insulin', 0)) > 0.6: risk_factors.append("مقاومة الأنسولين")
                    if float(data.get('BMI', 0)) > 0.7: risk_factors.append("مؤشر كتلة الجسم مرتفع")
                    if float(data.get('Systolic Blood Pressure', 0)) > 0.6: risk_factors.append("ارتفاع ضغط الدم")
                    if float(data.get('Triglycerides', 0)) > 0.6: risk_factors.append("ارتفاع الدهون الثلاثية")
                    if float(data.get('LDL Cholesterol', 0)) > 0.6: risk_factors.append("ارتفاع الكوليسترول الضار")
                    if float(data.get('HDL Cholesterol', 0)) < 0.4: risk_factors.append("انخفاض الكوليسترول النافع")
                    if float(data.get('ALT', 0)) > 0.6: risk_factors.append("ارتفاع إنزيمات الكبد")
                    if float(data.get('Creatinine', 0)) > 0.6: risk_factors.append("ارتفاع الكرياتينين")
                    if float(data.get('C-reactive Protein', 0)) > 0.6: risk_factors.append("ارتفاع بروتين سي التفاعلي")
                except Exception as e:
                    print(f"Error calculating risk factors: {str(e)}")
                
                # Store results
                self.prediction_results = {
                    "prediction": int(prediction[0]),
                    "result_text": self.get_result_text(int(prediction[0]), "DIABETES SCAN"),
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                
                return True
            
            elif test_type == "LIVER SCAN":
                if self.liver_model is None:
                    print("Error: Liver model is not loaded")
                    raise ValueError("Liver disease prediction model is not available")
                
                print("Processing liver data...")
                # Process and validate data
                df = self.process_liver_data(data)
                
                # Make prediction
                print("Making prediction...")
                prediction = self.liver_model.predict(df)
                prediction_proba = self.liver_model.predict_proba(df)
                print(f"Prediction: {prediction}")
                print(f"Prediction probability: {prediction_proba}")
                
                # Calculate risk factors
                print("Calculating risk factors...")
                risk_factors = []
                
                try:
                    if float(data['age']) > 50: risk_factors.append("Advanced age")
                    if float(data['total_bilirubin']) > 1.2: risk_factors.append("Elevated total bilirubin")
                    if float(data['direct_bilirubin']) > 0.3: risk_factors.append("Elevated direct bilirubin")
                    if float(data['alkphos']) > 150: risk_factors.append("Elevated alkaline phosphatase")
                    if float(data['sgpt']) > 50: risk_factors.append("Elevated SGPT")
                    if float(data['sgot']) > 50: risk_factors.append("Elevated SGOT")
                    if float(data['total_proteins']) < 6: risk_factors.append("Low total proteins")
                    if float(data['albumin']) < 3.5: risk_factors.append("Low albumin")
                    if float(data['ag_ratio']) < 1: risk_factors.append("Low A/G ratio")
                except Exception as e:
                    print(f"Error calculating risk factors: {str(e)}")
                    
                print(f"Identified risk factors: {risk_factors}")
                
                # Store results
                print("Storing prediction results...")
                self.prediction_results = {
                    "prediction": int(prediction[0]),
                    "result_text": self.get_result_text(int(prediction[0]), "LIVER SCAN"),
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                print(f"Stored prediction results: {self.prediction_results}")
                
                return True
                
            elif test_type == "CARDIAC HOLOGRAM":
                if self.heart_model is None:
                    raise ValueError("Heart disease prediction model is not available")
                
                # Process and validate data
                df = self.process_heart_data(data)
                
                # Make prediction
                prediction = self.heart_model.predict(df)
                prediction_proba = self.heart_model.predict_proba(df)
                
                # Calculate risk factors
                risk_factors = []
                if float(data['age']) > 55: risk_factors.append("Advanced age")
                if data['currentSmoker'] == 'Yes': risk_factors.append("Current smoker")
                if float(data['sysBP']) > 140 or float(data['diaBP']) > 90: risk_factors.append("High blood pressure")
                if float(data['totChol']) > 200: risk_factors.append("High cholesterol")
                if float(data['BMI']) > 30: risk_factors.append("Obesity")
                if float(data['glucose']) > 126: risk_factors.append("High glucose")
                
                # Store results
                self.prediction_results = {
                    "prediction": int(prediction[0]),
                    "result_text": self.get_result_text(int(prediction[0]), "HEART SCAN"),
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                
                return True
                
            elif test_type == "ANEMIA ANALYSIS":
                if self.anemia_model is None:
                    print("Error: Anemia model is not loaded")
                    raise ValueError("Anemia prediction model is not available")
                
                print("Processing anemia data...")
                # Process and validate data
                df = self.process_anemia_data(data)
                
                # Make prediction
                print("Making prediction...")
                prediction = self.anemia_model.predict(df)
                prediction_proba = self.anemia_model.predict_proba(df)
                print(f"Prediction: {prediction}")
                print(f"Prediction probability: {prediction_proba}")
                
                # Calculate risk factors
                print("Calculating risk factors...")
                risk_factors = []
                
                try:
                    # Check for anemia-related risk factors
                    if float(data['HGB']) < 12: risk_factors.append("Low hemoglobin")
                    if float(data['HCT']) < 36: risk_factors.append("Low hematocrit")
                    if float(data['RBC']) < 4: risk_factors.append("Low red blood cell count")
                    if float(data['MCV']) < 80: risk_factors.append("Microcytic anemia indication")
                    elif float(data['MCV']) > 100: risk_factors.append("Macrocytic anemia indication")
                    if float(data['MCH']) < 27: risk_factors.append("Low hemoglobin content")
                    if float(data['MCHC']) < 32: risk_factors.append("Low hemoglobin concentration")
                    if float(data['PLT']) < 150: risk_factors.append("Low platelet count")
                    if float(data['WBC']) < 4: risk_factors.append("Low white blood cell count")
                    
                except Exception as e:
                    print(f"Error calculating risk factors: {str(e)}")
                    
                print(f"Identified risk factors: {risk_factors}")
                
                # Store results
                print("Storing prediction results...")
                self.prediction_results = {
                    "prediction": int(prediction[0]),
                    "result_text": self.get_result_text(int(prediction[0]), "ANEMIA SCAN"),
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                print(f"Stored prediction results: {self.prediction_results}")
                
                return True
                
            return True
            
        except ValueError as e:
            print(f"Validation Error: {str(e)}")
            messagebox.showerror("Validation Error", str(e))
            return False
        except Exception as e:
            print(f"Analysis Error: {str(e)}")
            messagebox.showerror("Analysis Error", f"An error occurred during analysis: {str(e)}")
            return False

    def show_login(self):
        # مسح الواجهة السابقة
        for widget in self.root.winfo_children():
            widget.destroy()
            
        # إنشاء إطار تسجيل الدخول
        login_frame = ctk.CTkFrame(
            self.root,
            fg_color="#f5f9f0",
            corner_radius=30,
            border_width=2,
            border_color="#558b2f"
        )
        login_frame.pack(expand=True, padx=200, pady=100)

        # عنوان الصفحة
        title = ctk.CTkLabel(
            login_frame,
            text="VitaGuard AI",
            font=("Garamond", 36, "bold"),
            text_color="#2e7d32"
        )
        title.pack(pady=(40, 30))

        # أيقونة المستخدم
        user_icon = ctk.CTkLabel(
            login_frame,
            text="👤",
            font=("Arial", 48)
        )
        user_icon.pack(pady=(0, 20))

        # حقل اسم المستخدم
        username = ctk.CTkEntry(
            login_frame,
            placeholder_text="Username",
            width=300,
            height=50,
            corner_radius=10,
            border_color="#558b2f",
            fg_color="#ffffff",
            text_color="#1b5e20",
            placeholder_text_color="#81c784"
        )
        username.pack(pady=10, padx=40)

        # حقل كلمة المرور
        password = ctk.CTkEntry(
            login_frame,
            placeholder_text="Password",
            show="●",
            width=300,
            height=50,
            corner_radius=10,
            border_color="#558b2f",
            fg_color="#ffffff",
            text_color="#1b5e20",
            placeholder_text_color="#81c784"
        )
        password.pack(pady=10, padx=40)

        # زر تسجيل الدخول
        login_button = ctk.CTkButton(
            login_frame,
            text="LOGIN",
            command=self.show_main_menu,  # الانتقال للقائمة الرئيسية عند الضغط
            width=200,
            height=50,
            corner_radius=15,
            font=("Garamond", 20, "bold"),
            fg_color="#66bb6a",
            hover_color="#43a047",
            border_width=2,
            border_color="#2e7d32",
            text_color="#1b5e20"
        )
        login_button.pack(pady=(20, 40))

    def show_main_menu(self):
        # Clear previous widgets
        for widget in self.root.winfo_children():
            widget.destroy()
            
        # Create main container with sidebar and content
        container = ctk.CTkFrame(
            self.root,
            fg_color="#f1f8e9",
            corner_radius=0
        )
        container.pack(expand=True, fill="both")
        
        # Sidebar frame
        sidebar = ctk.CTkFrame(
            container,
            fg_color="#00FF7F",
            corner_radius=0,
            width=200
        )
        sidebar.pack(side="left", fill="y")
        sidebar.pack_propagate(False)

        # Add separator
        separator = ctk.CTkFrame(
            container,
            fg_color="#00CD66",  # Changed from black to green
            width=2,
            corner_radius=0
        )
        separator.pack(side="left", fill="y")
        
        # Add logout button
        logout_frame = ctk.CTkFrame(
            sidebar,
            fg_color="#00CD66",  # Changed from black to green
            corner_radius=20,
            height=50
        )
        logout_frame.pack(pady=(20, 10), padx=20, fill="x")
        
        logout_button = ctk.CTkButton(
            logout_frame,
            text="🚪 Logout",
            font=("Arial Black", 14),
            fg_color="transparent",
            hover_color="#00B359",  # Changed hover color to darker green
            text_color="white",
            corner_radius=20,
            height=40,
            width=160,
            command=self.show_login
        )
        logout_button.pack(pady=5, padx=5)
        
        # Search button
        search_frame = ctk.CTkFrame(
            sidebar,
            fg_color="#00CD66",  # Changed from black to green
            corner_radius=20,
            height=50
        )
        search_frame.pack(pady=(10, 30), padx=20, fill="x")
        
        search_button = ctk.CTkButton(
            search_frame,
            text="🔍 Search",
            font=("Arial Black", 14),
            fg_color="transparent",
            hover_color="#00B359",  # Changed hover color to darker green
            text_color="white",
            corner_radius=20,
            height=40,
            width=160
        )
        search_button.pack(pady=5, padx=5)
        
        # Menu items
        menu_items = [
            ("👤 Account", None, "Account Settings"),
            ("🕒 History", None, "View History"),
            ("⚙️ Settings", None, "System Settings")
        ]
        
        for text, command, tooltip in menu_items:
            button_frame = ctk.CTkFrame(
                sidebar,
                fg_color="#00CD66",  # Changed from #00CD66 to match new green theme
                corner_radius=15,
                height=45
            )
            button_frame.pack(pady=5, padx=20, fill="x")
            
            button = ctk.CTkButton(
                button_frame,
                text=text,
                font=("Arial", 14),
                fg_color="transparent",
                text_color="white",  # Changed from black to white for better contrast
                hover_color="#00B359",  # Changed hover color to darker green
                anchor="w",
                height=35,
                width=160
            )
            button.pack(pady=5, padx=5)
        
        # Content area
        content = ctk.CTkFrame(
            container,
            fg_color="#f1f8e9",
            corner_radius=0
        )
        content.pack(side="right", expand=True, fill="both")
        
        # Top buttons frame
        top_buttons_frame = ctk.CTkFrame(
            content,
            fg_color="#f1f8e9",
            corner_radius=30
        )
        top_buttons_frame.pack(fill="x", padx=40, pady=20)

        # Create a container frame for buttons with even spacing
        buttons_container = ctk.CTkFrame(
            top_buttons_frame,
            fg_color="transparent"
        )
        buttons_container.pack(fill="x", expand=True)

        # Configure grid columns for even spacing
        for i in range(4):
            buttons_container.grid_columnconfigure(i, weight=1)

        # Navigation buttons with their corresponding test types
        nav_buttons = [
            ("Kidney", "QUANTUM KIDNEY ANALYSIS"),
            ("Heart", "CARDIAC HOLOGRAM"),
            ("Liver", "LIVER SCAN"),
            ("Anemia", "ANEMIA ANALYSIS"),
            ("Diabetes", "NEURAL DIABETES SCAN")
        ]
        
        for i, (button_text, test_type) in enumerate(nav_buttons):
            button = ctk.CTkButton(
                buttons_container,
                text=button_text,
                width=180,  
                height=50,  
                corner_radius=25,  
                font=("Arial", 16, "bold"),  
                fg_color="#00CD66",
                hover_color="#00B359",
                text_color="white",  # Changed from black to white for better contrast
                border_width=2,
                border_color="#00B359",  # Changed from black to dark green
                command=lambda t=test_type: self.show_test_details(t)
            )
            button.grid(row=0, column=i, padx=15, pady=15)  
        
        # Add title
        title_label = ctk.CTkLabel(
            content,
            text="VitaGuard AI",
            font=("Garamond", 50, "bold"),
            text_color="#2e7d32"
        )
        title_label.pack(pady=(50, 30))
        
        # Create canvas for body image
        canvas = tk.Canvas(
            content,
            bg='#f1f8e9',
            highlightthickness=0,
            width=800,
            height=900
        )
        canvas.pack(expand=True, padx=20, pady=20)

        # Store button IDs and their regions
        self.button_ids = []
        self.button_regions = []
        
        try:
            # Load and display background image
            image_path = r"C:\Users\11\Desktop\x.png"
            print(f"Loading image from: {image_path}")
            
            try:
                pil_image = Image.open(image_path)
            except:
                image_path = r"C:\Users\11\Desktop\x.jpg"
                try:
                    pil_image = Image.open(image_path)
                except:
                    image_path = r"C:\Users\11\Desktop\x.jpeg"
                    pil_image = Image.open(image_path)
            
            # Resize and display background image
            resized_image = pil_image.resize((700, 800))
            self.body_image = ImageTk.PhotoImage(resized_image)
            canvas.create_image(400, 450, image=self.body_image, anchor='center')

            # Define button configurations
            button_configs = [
                {
                    "name": "CARDIAC HOLOGRAM",
                    "position": (410, 390),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\1.png"
                },
                {
                    "name": "QUANTUM KIDNEY ANALYSIS",
                    "position": (450, 485),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\2.png"
                },
                {
                    "name": "LIVER SCAN",
                    "position": (390, 450),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\4.png"
                },
                {
                    "name": "ANEMIA ANALYSIS",
                    "position": (245, 450),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\5.png"
                },
                {
                    "name": "NEURAL DIABETES SCAN",
                    "position": (320, 450),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\6.png"
                }
            ]

            # Store button images as instance variables to prevent garbage collection
            self.button_images = {}
            self.button_images_large = {}

            # Create and place buttons (initially hidden)
            for i, config in enumerate(button_configs):
                try:
                    # Load button image
                    img = Image.open(config["image_path"])
                    
                    # Create normal and hover size images
                    img_normal = img.resize(config["size"])
                    img_hover = img.resize(config["hover_size"])
                    
                    # Convert to PhotoImage and store
                    self.button_images[i] = ImageTk.PhotoImage(img_normal)
                    self.button_images_large[i] = ImageTk.PhotoImage(img_hover)
                    
                    # Calculate button region
                    x, y = config["position"]
                    w, h = config["size"]
                    region = (x - w//2, y - h//2, x + w//2, y + h//2)
                    self.button_regions.append(region)
                    
                    # Create button on canvas (initially hidden)
                    button_id = canvas.create_image(
                        config["position"][0],
                        config["position"][1],
                        image=self.button_images[i],
                        state='hidden',
                        tags=f"button_{i}"
                    )
                    self.button_ids.append(button_id)
                    
                    # Bind events
                    canvas.tag_bind(f"button_{i}", '<Enter>', 
                        lambda e, idx=i: self.on_button_hover(e, canvas, idx))
                    canvas.tag_bind(f"button_{i}", '<Leave>', 
                        lambda e, idx=i: self.on_button_leave(e, canvas, idx))
                    canvas.tag_bind(f"button_{i}", '<Button-1>', 
                        lambda e, test=config["name"]: self.show_test_details(test))
                    
                except Exception as e:
                    print(f"Error loading button image {i}: {str(e)}")

            def is_point_in_region(x, y, region):
                rx1, ry1, rx2, ry2 = region
                return rx1 <= x <= rx2 and ry1 <= y <= ry2

            # Bind mouse motion to canvas
            def on_mouse_motion(event):
                # Check each button region
                for i, region in enumerate(self.button_regions):
                    if is_point_in_region(event.x, event.y, region):
                        # Show only the button being hovered over
                        canvas.itemconfig(self.button_ids[i], state='normal')
                    else:
                        # Hide all other buttons
                        canvas.itemconfig(self.button_ids[i], state='hidden')

            def on_mouse_leave(event):
                # Hide all buttons when mouse leaves the canvas
                for button_id in self.button_ids:
                    canvas.itemconfig(button_id, state='hidden')

            # Bind mouse events to canvas
            canvas.bind('<Motion>', on_mouse_motion)
            canvas.bind('<Leave>', on_mouse_leave)

        except Exception as e:
            print(f"Error: {str(e)}")
            error_label = ctk.CTkLabel(
                canvas,
                text=f"Error loading image:\n{str(e)}",
                text_color="#ff0000",
                font=("Arial Black", 16)
            )
            error_label.pack(expand=True)

        # Create a button for entering the examination
        self.examination_button = ctk.CTkButton(
            self.root,
            text="Enter Examination",
            command=None,
            image=self.pancreas_photo,
            compound='top',
            width=180,
            height=50,
            corner_radius=10,
            font=('Arial', 16, 'bold'),
            fg_color='#4caf50',
            hover_color='#2e7d32'
        )
        self.examination_button.pack(pady=20)

    def on_button_hover(self, event, canvas, button_index):
        """Handle mouse hover over button"""
        canvas.itemconfig(f"button_{button_index}", image=self.button_images_large[button_index])

    def on_button_leave(self, event, canvas, button_index):
        """Handle mouse leave from button"""
        canvas.itemconfig(f"button_{button_index}", image=self.button_images[button_index])

    def show_test_details(self, test_type):
        self.current_test_type = test_type
        # Clear previous widgets
        for widget in self.root.winfo_children():
            widget.destroy()
            
        # Create container
        container = ctk.CTkFrame(
            self.root,
            fg_color="#f5f9f0",
            corner_radius=30,
            border_width=2,
            border_color="#558b2f"
        )
        container.pack(expand=True, fill="both", padx=30, pady=30)
        
        # Create top button frame
        top_button_frame = ctk.CTkFrame(
            container,
            fg_color="transparent"
        )
        top_button_frame.pack(anchor="nw", padx=30, pady=30, fill="x")
        
        # Back button
        back_button = ctk.CTkButton(
            top_button_frame,
            text="RETURN",
            command=self.show_main_menu,
            width=150,
            height=40,
            corner_radius=10,
            font=("Garamond", 14, "bold"),
            fg_color="#66bb6a",
            hover_color="#43a047",
            border_width=1,
            border_color="#2e7d32",
            text_color="#1b5e20"
        )
        back_button.pack(side="left", padx=5)

        # Load Test Data button
        load_data_button = ctk.CTkButton(
            top_button_frame,
            text="تحميل بيانات تجريبية",
            command=self.load_test_data,
            width=150,
            height=40,
            corner_radius=10,
            font=("Garamond", 14, "bold"),
            fg_color="#66bb6a",
            hover_color="#43a047",
            border_width=1,
            border_color="#2e7d32",
            text_color="#1b5e20"
        )
        load_data_button.pack(side="left", padx=5)

        # Create form frame
        form_frame = ctk.CTkFrame(
            container,
            fg_color="transparent"
        )
        form_frame.pack(fill="both", padx=30, pady=(0, 20), anchor="n")
        form_frame.grid_rowconfigure(0, weight=1)
        form_frame.grid_columnconfigure(0, weight=1)


        
        def load_test_data():
            if test_type == "NEURAL DIABETES SCAN":
                # Load predefined test values for diabetes
                test_values = {
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
                
                # Update entry fields with test values
                for param, value in test_values.items():
                    if param in self.input_widgets:
                        self.input_widgets[param].delete(0, tk.END)
                        self.input_widgets[param].insert(0, str(value))
                        
                messagebox.showinfo("تم التحميل", "تم تحميل بيانات الاختبار بنجاح")
            elif test_type == "QUANTUM KIDNEY ANALYSIS":
                fields = [
                    {"name": "Age of the patient", "key": "Age", "placeholder": "عمر المريض", "type": "entry"},
                    {"name": "Gender of the patient", "key": "Gender", "placeholder": "جنس المريض", "type": "combobox", "values": ["Male", "Female"]},
                    {"name": "Total Bilirubin", "key": "Total_Bilirubin", "placeholder": "البيليروبين الكلي", "type": "entry"},
                    {"name": "Direct Bilirubin", "key": "Direct_Bilirubin", "placeholder": "البيليروبين المباشر", "type": "entry"},
                    {"name": "Alkphos Alkaline Phosphotase", "key": "Alkphos_Alkaline_Phosphotase", "placeholder": "الفوسفاتيز القلوي", "type": "entry"},
                    {"name": "Sgpt Alamine Aminotransferase", "key": "Sgpt_Alamine_Aminotransferase", "placeholder": "ناقلة أمين الألانين", "type": "entry"},
                    {"name": "Sgot Aspartate Aminotransferase", "key": "Sgot_Aspartate_Aminotransferase", "placeholder": "ناقلة أمين الأسبارتات", "type": "entry"},
                    {"name": "Total Protiens", "key": "Total_Protiens", "placeholder": "البروتينات الكلية", "type": "entry"},
                    {"name": "ALB Albumin", "key": "ALB_Albumin", "placeholder": "الألبومين", "type": "entry"},
                    {"name": "A/G Ratio Albumin and Globulin Ratio", "key": "AG_Ratio", "placeholder": "نسبة الألبومين/الجلوبيولين", "type": "entry"}
                ]

                # Create input fields
                for i, field in enumerate(fields):
                    label = ctk.CTkLabel(
                        form_frame,
                        text=field["name"],
                        font=("Arial", 14),
                        text_color="#2e7d32"
                    )
                    label.grid(row=i, column=0, padx=10, pady=5, sticky="e")

                    if field["type"] == "combobox":
                        widget = ctk.CTkComboBox(
                            form_frame,
                            values=field["values"],
                            width=300,
                            font=("Arial", 12),
                            dropdown_font=("Arial", 12)
                        )
                        widget.set(field["placeholder"])
                    else:
                        widget = ctk.CTkEntry(
                            form_frame,
                            placeholder_text=field["placeholder"],
                            width=300,
                            font=("Arial", 12)
                        )

                    widget.grid(row=i, column=1, padx=10, pady=5, sticky="w")
                    self.input_widgets[field["key"]] = widget
            elif test_type == "LIVER SCAN":
                test_data = {
                    "age": "45",
                    "gender": "Female",
                    "total_bilirubin": "0.7",
                    "direct_bilirubin": "0.1",
                    "alkphos": "187",
                    "sgpt": "16",
                    "sgot": "18",
                    "total_proteins": "6.8",
                    "albumin": "3.3",
                    "ag_ratio": "0.9"
                }
                
                # Populate form fields with test data
                for key, value in test_data.items():
                    if key in self.input_widgets:
                        widget = self.input_widgets[key]
                        print(f"Setting {key} to {value}")
                        
                        if isinstance(widget, ctk.CTkComboBox):
                            widget.set(value)
                        elif isinstance(widget, ctk.CTkEntry):
                            widget.delete(0, 'end')
                            widget.insert(0, value)
                
                print("Test data loaded successfully")
            elif test_type == "CARDIAC HOLOGRAM":
                test_data = {
                    "male": "Male",
                    "age": "65",
                    "currentSmoker": "Yes",
                    "cigsPerDay": "20",
                    "BPMeds": "Yes",
                    "prevalentStroke": "No",
                    "prevalentHyp": "Yes",
                    "diabetes": "Yes",
                    "totChol": "280",
                    "sysBP": "160",
                    "diaBP": "95",
                    "BMI": "31.5",
                    "heartRate": "88",
                    "glucose": "180"
                }
            elif test_type == "ANEMIA ANALYSIS":
                test_data = {
                    "WBC": "3.1",          # منخفض - يشير إلى ضعف في المناعة
                    "LYMp": "15",          # منخفض - خلل في الخلايا الليمفاوية
                    "NEUTp": "80",         # مرتفع - قد يشير إلى التهاب
                    "LYMn": "0.8",         # منخفض جداً
                    "NEUTn": "6.5",        # مرتفع
                    "RBC": "3.2",          # منخفض جداً - فقر دم واضح
                    "HGB": "8.5",          # منخفض جداً - نقص حاد في الهيموجلوبين
                    "HCT": "28",           # منخفض جداً
                    "MCV": "65",           # منخفض - يشير إلى فقر دم من نقص الحديد
                    "MCH": "22",           # منخفض
                    "MCHC": "29",          # منخفض
                    "PLT": "420",          # مرتفع - قد يشير إلى التهاب مزمن
                    "PDW": "19.5",         # مرتفع
                    "PCT": "0.45"          # مرتفع
                }
            elif test_type == "NEURAL DIABETES SCAN":
                if self.diabetes_model is None:
                    print("Error: Diabetes model is not loaded")
                    raise ValueError("Diabetes prediction model is not available")
                
                print("Processing diabetes data...")
                # Create DataFrame with the correct structure
                df = pd.DataFrame([test_data], columns=[
                    'Glucose', 'Cholesterol', 'Hemoglobin', 'Platelets', 'White Blood Cells', 
                    'Red Blood Cells', 'Hematocrit', 'Mean Corpuscular Volume', 
                    'Mean Corpuscular Hemoglobin', 'Mean Corpuscular Hemoglobin Concentration', 
                    'Insulin', 'BMI', 'Systolic Blood Pressure', 'Diastolic Blood Pressure', 
                    'Triglycerides', 'HbA1c', 'LDL Cholesterol', 'HDL Cholesterol', 
                    'ALT', 'AST', 'Heart Rate', 'Creatinine', 'Troponin', 'C-reactive Protein'
                ])
                
                # Add missing columns if any
                training_columns = self.diabetes_model.named_steps['preprocessor'].transformers_[0][2]
                missing_columns = set(training_columns) - set(df.columns)
                for col in missing_columns:
                    df[col] = np.nan
                
                # Reorder columns to match training data
                df = df[training_columns]
                
                # Make prediction
                print("Making prediction...")
                prediction = self.diabetes_model.predict(df)
                prediction_proba = self.diabetes_model.predict_proba(df)
                
                # Calculate risk factors
                
                
                # Store results
                self.prediction_results = {
                    "prediction": int(prediction[0]),
                    "result_text": self.get_result_text(int(prediction[0]), "DIABETES SCAN"),
                    "probability": prediction_proba[0][1]
                }
                
                return True
            
            # Populate form fields with test data
            for key, value in test_data.items():
                if key in self.input_widgets:
                    widget = self.input_widgets[key]
                    print(f"Setting {key} to {value}")
                    
                    # Handle different widget types
                    if isinstance(widget, ctk.CTkComboBox):
                        widget.set(value)
                    elif isinstance(widget, ctk.CTkEntry):
                        widget.delete(0, 'end')  # Clear existing text
                        widget.insert(0, value)  # Insert new value
                    
            print("Test data loaded successfully")
        
        # Add Test Data button if this is the kidney analysis or cardiac test form
        if test_type in ["QUANTUM KIDNEY ANALYSIS", "CARDIAC HOLOGRAM", "LIVER SCAN", "ANEMIA ANALYSIS", "NEURAL DIABETES SCAN"]:
            test_button = ctk.CTkButton(
                top_button_frame,
                text="LOAD TEST DATA",
                command=load_test_data,
                width=150,
                height=40,
                corner_radius=10,
                font=("Garamond", 14, "bold"),
                fg_color="#4CAF50",
                hover_color="#388E3C",
                border_width=1,
                border_color="#2e7d32",
                text_color="#ffffff"
            )
            test_button.pack(side="left", padx=5)
        
        # Title
        title = ctk.CTkLabel(
            container,
            text=test_type,
            font=("Garamond", 36, "bold"),
            text_color="#2e7d32"
        )
        title.pack(pady=20)

        # Create scrollable frame for parameters
        screen_height = self.root.winfo_screenheight()
        scrollable_frame = ctk.CTkScrollableFrame(
            container,
            fg_color="transparent",
            height=screen_height - 200  # خصم بسيط من ارتفاع الشاشة
        )

        scrollable_frame.pack(fill="both", padx=30, pady=(0, 20), anchor="n")


        
        # Dictionary of parameters for each test type
        params = {
            "NEURAL DIABETES SCAN": [
                # Blood Sugar Parameters
                {"name": "Glucose", "key": "Glucose", "type": "number", "placeholder": "Enter glucose level"},
                {"name": "HbA1c", "key": "HbA1c", "type": "number", "placeholder": "Enter HbA1c value"},
                {"name": "Insulin", "key": "Insulin", "type": "number", "placeholder": "Enter insulin level"},
                
                # Lipid Profile
                {"name": "Cholesterol", "key": "Cholesterol", "type": "number", "placeholder": "Enter cholesterol level"},
                {"name": "Triglycerides", "key": "Triglycerides", "type": "number", "placeholder": "Enter triglycerides level"},
                {"name": "LDL Cholesterol", "key": "LDL Cholesterol", "type": "number", "placeholder": "Enter LDL"},
                {"name": "HDL Cholesterol", "key": "HDL Cholesterol", "type": "number", "placeholder": "Enter HDL"},
                
                # Complete Blood Count
                {"name": "Hemoglobin", "key": "Hemoglobin", "type": "number", "placeholder": "Enter hemoglobin level"},
                {"name": "Platelets", "key": "Platelets", "type": "number", "placeholder": "Enter platelets count"},
                {"name": "White Blood Cells", "key": "White Blood Cells", "type": "number", "placeholder": "Enter WBC count"},
                {"name": "Red Blood Cells", "key": "Red Blood Cells", "type": "number", "placeholder": "Enter RBC count"},
                {"name": "Hematocrit", "key": "Hematocrit", "type": "number", "placeholder": "Enter hematocrit value"},
                
                # Blood Cell Indices
                {"name": "Mean Corpuscular Volume", "key": "Mean Corpuscular Volume", "type": "number", "placeholder": "Enter MCV"},
                {"name": "Mean Corpuscular Hemoglobin", "key": "Mean Corpuscular Hemoglobin", "type": "number", "placeholder": "Enter MCH"},
                {"name": "Mean Corpuscular Hemoglobin Concentration", "key": "Mean Corpuscular Hemoglobin Concentration", "type": "number", "placeholder": "Enter MCHC"},
                
                # Vital Signs
                {"name": "BMI", "key": "BMI", "type": "number", "placeholder": "Enter BMI"},
                {"name": "Systolic Blood Pressure", "key": "Systolic Blood Pressure", "type": "number", "placeholder": "Enter systolic BP"},
                {"name": "Diastolic Blood Pressure", "key": "Diastolic Blood Pressure", "type": "number", "placeholder": "Enter diastolic BP"},
                {"name": "Heart Rate", "key": "Heart Rate", "type": "number", "placeholder": "Enter heart rate"},
                
                # Liver Function Tests
                {"name": "ALT", "key": "ALT", "type": "number", "placeholder": "Enter ALT level"},
                {"name": "AST", "key": "AST", "type": "number", "placeholder": "Enter AST level"},
                
                # Kidney Function
                {"name": "Creatinine", "key": "Creatinine", "type": "number", "placeholder": "Enter creatinine level"},
                
                # Cardiac Markers
                {"name": "Troponin", "key": "Troponin", "type": "number", "placeholder": "Enter troponin level"},
                
                # Inflammatory Markers
                {"name": "C-reactive Protein", "key": "C-reactive Protein", "type": "number", "placeholder": "Enter CRP level"}
            ],
            "QUANTUM KIDNEY ANALYSIS": [
                # Basic Demographics
                {"name": "Age", "key": "Age", "type": "number", "placeholder": "Enter age"},
                {"name": "Gender", "key": "Gender", "type": "combobox", "values": ["Male", "Female"], "placeholder": "Select gender"},
                {"name": "BMI", "key": "BMI", "type": "number", "placeholder": "Enter BMI"},
                
                # Lifestyle and History
                {"name": "Smoking", "key": "Smoking", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Smoking status"},
                {"name": "Alcohol Consumption", "key": "AlcoholConsumption", "type": "number", "placeholder": "Units per week"},
                {"name": "Family History of Kidney Disease", "key": "FamilyHistoryKidneyDisease", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Family history of kidney disease"},
                {"name": "Family History of Hypertension", "key": "FamilyHistoryHypertension", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Family history of hypertension"},
                {"name": "Family History of Diabetes", "key": "FamilyHistoryDiabetes", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Family history of diabetes"},
                {"name": "Previous Acute Kidney Injury", "key": "PreviousAcuteKidneyInjury", "type": "combobox", "values": ["Yes", "No"], "placeholder": "History of acute kidney injury"},
                
                # Vital Signs
                {"name": "Systolic Blood Pressure", "key": "SystolicBP", "type": "number", "placeholder": "Enter systolic BP"},
                {"name": "Diastolic Blood Pressure", "key": "DiastolicBP", "type": "number", "placeholder": "Enter diastolic BP"},
                
                # Blood Tests
                {"name": "Fasting Blood Sugar", "key": "FastingBloodSugar", "type": "number", "placeholder": "Enter fasting blood sugar"},
                {"name": "HbA1c Level", "key": "HbA1c", "type": "number", "placeholder": "Enter HbA1c level"},
                {"name": "Serum Creatinine", "key": "SerumCreatinine", "type": "number", "placeholder": "Enter serum creatinine"},
                {"name": "BUN Levels", "key": "BUNLevels", "type": "number", "placeholder": "Enter BUN levels"},
                {"name": "GFR", "key": "GFR", "type": "number", "placeholder": "Enter GFR value"},
                
                # Urine Tests
                {"name": "Protein in Urine", "key": "ProteinInUrine", "type": "number", "placeholder": "Enter protein in urine"},
                {"name": "Albumin/Creatinine Ratio (ACR)", "key": "ACR", "type": "number", "placeholder": "Enter ACR"},
                
                # Electrolytes
                {"name": "Serum Sodium", "key": "SerumElectrolytesSodium", "type": "number", "placeholder": "Enter sodium level"},
                {"name": "Serum Potassium", "key": "SerumElectrolytesPotassium", "type": "number", "placeholder": "Enter potassium level"},
                {"name": "Serum Calcium", "key": "SerumElectrolytesCalcium", "type": "number", "placeholder": "Enter calcium level"},
                {"name": "Serum Phosphorus", "key": "SerumElectrolytesPhosphorus", "type": "number", "placeholder": "Enter phosphorus level"},
                
                # Additional Tests
                {"name": "Hemoglobin Levels", "key": "HemoglobinLevels", "type": "number", "placeholder": "Enter hemoglobin level"},
                
                # Medications and Symptoms
                {"name": "Diuretics Usage", "key": "Diuretics", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Using diuretics?"},
                {"name": "Edema", "key": "Edema", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Presence of edema"},
                {"name": "Fatigue Level (1-10)", "key": "FatigueLevels", "type": "number", "placeholder": "Enter fatigue level"},
                {"name": "Nausea/Vomiting", "key": "NauseaVomiting", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Presence of nausea/vomiting"},
                {"name": "Muscle Cramps", "key": "MuscleCramps", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Presence of muscle cramps"},
                {"name": "Itching", "key": "Itching", "type": "combobox", "values": ["Yes", "No"], "placeholder": "Presence of itching"}
            ],
            "CARDIAC HOLOGRAM": [
                {"name": "Gender", "key": "male", "type": "combobox", "values": ["Male", "Female"], "placeholder": "Select gender"},
                {"name": "Age", "key": "age", "type": "number", "placeholder": "Enter age"},
                {"name": "Current Smoker", "key": "currentSmoker", "type": "combobox", "values": ["No", "Yes"], "placeholder": "Select smoking status"},
                {"name": "Cigarettes Per Day", "key": "cigsPerDay", "type": "number", "placeholder": "Number of cigarettes per day"},
                {"name": "Blood Pressure Medications", "key": "BPMeds", "type": "combobox", "values": ["No", "Yes"], "placeholder": "On BP medication?"},
                {"name": "Prevalent Stroke", "key": "prevalentStroke", "type": "combobox", "values": ["No", "Yes"], "placeholder": "History of stroke?"},
                {"name": "Prevalent Hypertension", "key": "prevalentHyp", "type": "combobox", "values": ["No", "Yes"], "placeholder": "History of hypertension?"},
                {"name": "Diabetes", "key": "diabetes", "type": "combobox", "values": ["No", "Yes"], "placeholder": "Diabetes diagnosis?"},
                {"name": "Total Cholesterol", "key": "totChol", "type": "number", "placeholder": "Total cholesterol level"},
                {"name": "Systolic Blood Pressure", "key": "sysBP", "type": "number", "placeholder": "Systolic BP"},
                {"name": "Diastolic Blood Pressure", "key": "diaBP", "type": "number", "placeholder": "Diastolic BP"},
                {"name": "BMI", "key": "BMI", "type": "number", "placeholder": "Body Mass Index"},
                {"name": "Heart Rate", "key": "heartRate", "type": "number", "placeholder": "Heart rate (BPM)"},
                {"name": "Glucose Level", "key": "glucose", "type": "number", "placeholder": "Blood glucose level"}
            ],
            "LIVER SCAN": [
                # Demographics
                {"name": "Age", "key": "age", "type": "number", "placeholder": "Enter age"},
                {"name": "Gender", "key": "gender", "type": "combobox", "values": ["1", "0"], "placeholder": "Select gender"},
                
                # Liver Function Tests
                {"name": "Total Bilirubin", "key": "total_bilirubin", "type": "number", "placeholder": "Enter Total Bilirubin"},
                {"name": "Direct Bilirubin", "key": "direct_bilirubin", "type": "number", "placeholder": "Enter Direct Bilirubin"},
                {"name": "Alkaline Phosphotase", "key": "alkphos", "type": "number", "placeholder": "Enter Alkphos Level"},
                {"name": "SGPT (ALT)", "key": "sgpt", "type": "number", "placeholder": "Enter SGPT Level"},
                {"name": "SGOT (AST)", "key": "sgot", "type": "number", "placeholder": "Enter SGOT Level"},
                {"name": "Total Proteins", "key": "total_proteins", "type": "number", "placeholder": "Enter Total Proteins Level"},
                {"name": "Albumin", "key": "albumin", "type": "number", "placeholder": "Enter Albumin Level"},
                {"name": "Albumin/Globulin Ratio", "key": "ag_ratio", "type": "number", "placeholder": "Enter A/G Ratio"}
            ],
            "ANEMIA ANALYSIS": [
                # Complete Blood Count
                {"name": "White Blood Cell Count (WBC)", "key": "WBC", "type": "number", "placeholder": "Enter WBC (K/µL)"},
                {"name": "Lymphocyte % (LYMp)", "key": "LYMp", "type": "number", "placeholder": "Enter Lymphocyte %"},
                {"name": "Neutrophil % (NEUTp)", "key": "NEUTp", "type": "number", "placeholder": "Enter Neutrophil %"},
                {"name": "Lymphocyte Count (LYMn)", "key": "LYMn", "type": "number", "placeholder": "Enter Lymphocyte Count (K/µL)"},
                {"name": "Neutrophil Count (NEUTn)", "key": "NEUTn", "type": "number", "placeholder": "Enter Neutrophil Count (K/µL)"},
                
                # Red Blood Cell Parameters
                {"name": "Red Blood Cell Count (RBC)", "key": "RBC", "type": "number", "placeholder": "Enter RBC (M/µL)"},
                {"name": "Hemoglobin (HGB)", "key": "HGB", "type": "number", "placeholder": "Enter Hemoglobin (g/dL)"},
                {"name": "Hematocrit (HCT)", "key": "HCT", "type": "number", "placeholder": "Enter Hematocrit (%)"},
                {"name": "Mean Corpuscular Volume (MCV)", "key": "MCV", "type": "number", "placeholder": "Enter MCV (fL)"},
                {"name": "Mean Corpuscular Hemoglobin (MCH)", "key": "MCH", "type": "number", "placeholder": "Enter MCH (pg)"},
                {"name": "Mean Corpuscular Hemoglobin Concentration (MCHC)", "key": "MCHC", "type": "number", "placeholder": "Enter MCHC (g/dL)"},
                
                # Platelet Parameters
                {"name": "Platelet Count (PLT)", "key": "PLT", "type": "number", "placeholder": "Enter Platelet Count (K/µL)"},
                {"name": "Platelet Distribution Width (PDW)", "key": "PDW", "type": "number", "placeholder": "Enter PDW (%)"},
                {"name": "Plateletcrit (PCT)", "key": "PCT", "type": "number", "placeholder": "Enter PCT (%)"}
            ]
        }

        # Store input widgets
        self.input_widgets = {}
        
        if test_type in ["QUANTUM KIDNEY ANALYSIS", "CARDIAC HOLOGRAM", "LIVER SCAN", "ANEMIA ANALYSIS", "NEURAL DIABETES SCAN"]:
            print(f"Setting up form for {test_type}")
            # Create input fields for parameters
            for param in params[test_type]:
                param_frame = ctk.CTkFrame(scrollable_frame, fg_color="transparent")
                param_frame.pack(pady=12, padx=30, fill="x")
                
                label = ctk.CTkLabel(
                    param_frame,
                    text=param["name"],
                    font=("Garamond", 14, "bold"),
                    text_color="#2e7d32"
                )
                label.pack(side="left", padx=15)
                
                if param["type"] == "combobox":
                    widget = ctk.CTkComboBox(
                        param_frame,
                        values=param["values"],
                        width=350,
                        height=40,
                        corner_radius=10,
                        border_color="#558b2f",
                        fg_color="#ffffff",
                        text_color="#1b5e20",
                        button_color="#66bb6a",
                        button_hover_color="#43a047",
                        dropdown_fg_color="#ffffff",
                        dropdown_hover_color="#e8f5e9",
                        dropdown_text_color="#1b5e20"
                    )
                    widget.set(param["placeholder"])
                else:
                    widget = ctk.CTkEntry(
                        param_frame,
                        placeholder_text=param["placeholder"],
                        width=350,
                        height=40,
                        corner_radius=10,
                        border_color="#558b2f",
                        fg_color="#ffffff",
                        text_color="#1b5e20",
                        placeholder_text_color="#81c784"
                    )
                widget.pack(side="right", padx=15)
                self.input_widgets[param["key"]] = widget
                print(f"Created input widget for {param['key']}")
        
        def validate_and_analyze():
            if test_type in ["QUANTUM KIDNEY ANALYSIS", "CARDIAC HOLOGRAM", "LIVER SCAN", "ANEMIA ANALYSIS", "NEURAL DIABETES SCAN"]:
                try:
                    print(f"Starting validation for {test_type}")
                    # Collect data from input widgets
                    data = {}
                    for key, widget in self.input_widgets.items():
                        value = widget.get()
                        print(f"Got value for {key}: {value}")
                        
                        if value in ["", "Select gender", "Select smoking status"]:
                            raise ValueError(f"Please fill in all fields. Missing: {key}")
                        
                        # Convert Yes/No to 1/0 for boolean fields
                        if value in ["Yes", "No"]:
                            value = 1 if value == "Yes" else 0
                        # Convert Male/Female to 1/0 for gender
                        elif (key == "Gender" and test_type == "QUANTUM KIDNEY ANALYSIS") or (key == "male" and test_type == "CARDIAC HOLOGRAM"):
                            value = 1 if value == "Male" else 0
                        # Convert numeric fields
                        elif key not in ["Gender", "Smoking", "FamilyHistoryKidneyDisease", 
                                       "FamilyHistoryHypertension", "FamilyHistoryDiabetes",
                                       "PreviousAcuteKidneyInjury", "Diuretics", "Edema",
                                       "NauseaVomiting", "MuscleCramps", "Itching"]:
                            try:
                                value = float(value)
                            except ValueError:
                                raise ValueError(f"Please enter a valid number for {key}")
                        
                        data[key] = value
                        print(f"Processed value for {key}: {value}")
                    
                    print("Collected all form data:", data)
                    # Validate and analyze data
                    if self.validate_and_analyze(test_type, data):
                        print("Analysis successful, showing results")
                        self.show_results(test_type)
                    else:
                        print("Analysis failed")
                    
                except ValueError as e:
                    print(f"Validation error: {str(e)}")
                    messagebox.showerror("Validation Error", str(e))
                except Exception as e:
                    print(f"Unexpected error: {str(e)}")
                    messagebox.showerror("Error", f"An unexpected error occurred: {str(e)}")
            else:
                self.show_results(test_type)
        
        # Create action buttons
        button_frame = ctk.CTkFrame(scrollable_frame, fg_color="transparent")
        button_frame.pack(pady=30, padx=30, fill="x")
        
        # Analyze button
        analyze_button = ctk.CTkButton(
            button_frame,
            text="ANALYZE",
            command=validate_and_analyze,
            width=200,
            height=50,
            corner_radius=10,
            font=("Garamond", 16, "bold"),
            fg_color="#66bb6a",
            hover_color="#43a047",
            border_width=1,
            border_color="#2e7d32",
            text_color="#1b5e20"
        )
        analyze_button.pack(side="right", padx=15)
        
        # Back button
        back_button = ctk.CTkButton(
            button_frame,
            text="BACK",
            command=self.show_main_menu,
            width=200,
            height=50,
            corner_radius=10,
            font=("Garamond", 16, "bold"),
            fg_color="#4db6ac",
            hover_color="#00897b",
            border_width=1,
            border_color="#004d40",
            text_color="#ffffff"
        )
        back_button.pack(side="right", padx=15)

    def show_results(self, test_type):
        # Create results window
        results_window = ctk.CTkToplevel(self.root)
        results_window.title("Medical Analysis Results")
        results_window.geometry("1920x1080")  # Full HD resolution
        results_window.configure(fg_color="#f1f8e9")

        # Create main frame
        main_frame = ctk.CTkFrame(
            results_window,
            fg_color="#f1f8e9",
            corner_radius=20
        )
        main_frame.pack(expand=True, fill="both", padx=40, pady=40)

        # Show test type header
        header_label = ctk.CTkLabel(
            main_frame,
            text=f"{test_type} - Medical Assessment Report",
            font=("Arial", 32, "bold"),  # Increased font size
            text_color="#2e7d32"
        )
        header_label.pack(pady=20)

        # Calculate diagnosis status
        probability = self.prediction_results["probability"] * 100
        is_diagnosed = probability >= 50
        diagnosis_status = "Diagnosed (Positive)" if is_diagnosed else "Not Diagnosed (Negative)"

        # Determine risk level and colors
        if probability >= 75:
            risk_level = "Very High Risk"
            risk_color = "#d32f2f"
            risk_symbol = "⚠️"
        elif probability >= 50:
            risk_level = "High Risk"
            risk_color = "#c62828"
            risk_symbol = "⚠️"
        elif probability >= 25:
            risk_level = "Moderate Risk"
            risk_color = "#f57c00"
            risk_symbol = "⚡"
        else:
            risk_level = "Low Risk"
            risk_color = "#2e7d32"
            risk_symbol = "✓"

        # Create image frame
        image_frame = ctk.CTkFrame(
            main_frame,
            fg_color="transparent"
        )
        image_frame.pack(pady=20)

        # Load the appropriate image based on diagnosis status and test type
        base_path = r"C:\Users\11\Desktop\work\dr"
        if is_diagnosed:
            image_paths = {
                "ANEMIA ANALYSIS": os.path.join(base_path, "badanemia.png"),
                "CARDIAC HOLOGRAM": os.path.join(base_path, "badheart.png"),
                "QUANTUM KIDNEY ANALYSIS": os.path.join(base_path, "badkidneys.png"),
                "LIVER SCAN": os.path.join(base_path, "badliver.png")
            }
            image_path = image_paths.get(test_type, os.path.join(base_path, "x.png"))
        else:
            image_path = os.path.join(base_path, "x.png")

        # Load and display the image
        try:
            print(f"Attempting to load image from: {image_path}")
            if os.path.exists(image_path):
                image = Image.open(image_path)
                image = image.resize((600, 600))  # Increased image size to 600x600
                photo = ImageTk.PhotoImage(image)

                image_label = ctk.CTkLabel(
                    image_frame,
                    image=photo,
                    text=""
                )
                image_label.image = photo  # Keep a reference!
                image_label.pack()
            else:
                print(f"Image file not found at: {image_path}")
        except Exception as e:
            print(f"Image load error: {str(e)}")

        # Show diagnosis status
        diagnosis_frame = ctk.CTkFrame(
            main_frame,
            fg_color="#e8f5e9",
            corner_radius=15
        )
        diagnosis_frame.pack(fill="x", padx=40, pady=20)
        
        diagnosis_label = ctk.CTkLabel(
            diagnosis_frame,
            text=f"Diagnosis Status: {diagnosis_status}",
            font=("Arial", 28, "bold"),  # Increased font size
            text_color=risk_color
        )
        diagnosis_label.pack(pady=10)

        # Only open new diagnostic test for diabetes if diagnosed
        if test_type == "NEURAL DIABETES SCAN" and is_diagnosed:
            # Close the results window
            results_window.destroy()
            # Show only the new diagnostic test
            self.show_new_diagnostic_test(self.prediction_results["input_data"])
            return

        # Show risk assessment
        risk_label = ctk.CTkLabel(
            diagnosis_frame,
            text=f"Risk Assessment: {risk_symbol} {risk_level}",
            font=("Arial", 24, "bold"),  # Increased font size
            text_color=risk_color
        )
        risk_label.pack(pady=10)

        # Show probability with interpretation
        prob_text = f"Risk Probability: {probability:.1f}%"
        interpretation = ""
        if is_diagnosed:
            interpretation = "(Immediate medical attention required)"
        else:
            interpretation = "(Regular monitoring recommended)"

        prob_label = ctk.CTkLabel(
            diagnosis_frame,
            text=f"{prob_text}\n{interpretation}",
            font=("Arial", 20),  # Increased font size
            text_color=risk_color
        )
        prob_label.pack(pady=10)

        # Footer note
        footer_note = ctk.CTkLabel(
            main_frame,
            text="This report is generated based on AI analysis and should be reviewed by a healthcare professional.",
            font=("Arial", 14),  # Increased font size
            text_color="#66bb6a"
        )
        footer_note.pack(pady=20)

        # After determining diagnosis status, add this condition:
        if test_type == "NEURAL DIABETES SCAN" and is_diagnosed:
            self.show_detailed_diabetes_analysis(self.prediction_results["input_data"])

    def translate_risk_factor(self, arabic_factor):
        """Translate Arabic risk factors to English"""
        translations = {
            "ارتفاع مستوى السكر في الدم": "High Blood Glucose Level",
            "ارتفاع مستوى الهيموجلوبين السكري": "Elevated HbA1c",
            "مقاومة الأنسولين": "Insulin Resistance",
            "مؤشر كتلة الجسم مرتفع": "High Body Mass Index",
            "ارتفاع ضغط الدم": "High Blood Pressure",
            "ارتفاع الدهون الثلاثية": "Elevated Triglycerides",
            "ارتفاع الكوليسترول الضار": "High LDL Cholesterol",
            "انخفاض الكوليسترول النافع": "Low HDL Cholesterol",
            "ارتفاع إنزيمات الكبد": "Elevated Liver Enzymes",
            "ارتفاع الكرياتينين": "High Creatinine",
            "ارتفاع بروتين سي التفاعلي": "Elevated C-reactive Protein"
        }
        return translations.get(arabic_factor, arabic_factor)  # Return original if no translation found

    def run(self):
        self.root.mainloop()

    def load_test_data(self):
        """Load test data based on test type"""
        if self.current_test_type == "CARDIAC HOLOGRAM":
            test_data = {
                "male": "Male",
                "age": "65",
                "currentSmoker": "Yes",
                "cigsPerDay": "20",
                "BPMeds": "Yes",
                "prevalentStroke": "No",
                "prevalentHyp": "Yes",
                "diabetes": "Yes",
                "totChol": "280",
                "sysBP": "160",
                "diaBP": "95",
                "BMI": "31.5",
                "heartRate": "88",
                "glucose": "180"
            }
            
            # Update input fields with test data
            for key, value in test_data.items():
                if key in self.input_widgets:
                    widget = self.input_widgets[key]
                    if isinstance(widget, ctk.CTkComboBox):
                        widget.set(value)
                    else:
                        widget.delete(0, tk.END)
                        widget.insert(0, value)
            
            messagebox.showinfo("Test Data", "Cardiac test data loaded successfully")
        elif self.current_test_type == "NEURAL DIABETES SCAN":
            # ... existing diabetes test data code ...
            pass
        elif self.current_test_type == "QUANTUM KIDNEY ANALYSIS":
            # Your provided test data
            test_data = [71, 0, 31.07, 1, 5.12, 0, 0, 0, 0, 113, 83, 72.51, 9.21, 4.96, 25.60, 45.70, 0.74, 123.84, 137.65, 3.62, 10.31, 3.15, 16.11, 0, 0, 3.56, 6.99, 4.51, 7.55]
            
            # Map the data to field keys
            field_mapping = {
                "Age": test_data[0],
                "Gender": "Male" if test_data[1] == 0 else "Female",
                "BMI": test_data[2],
                "Smoking": test_data[3],
                "AlcoholConsumption": test_data[4],
                "FamilyHistoryKidneyDisease": test_data[5],
                "FamilyHistoryHypertension": test_data[6],
                "FamilyHistoryDiabetes": test_data[7],
                "PreviousAcuteKidneyInjury": test_data[8],
                "SystolicBP": test_data[9],
                "DiastolicBP": test_data[10],
                "FastingBloodSugar": test_data[11],
                "HbA1c": test_data[12],
                "SerumCreatinine": test_data[13],
                "BUNLevels": test_data[14],
                "GFR": test_data[15],
                "ProteinInUrine": test_data[16],
                "ACR": test_data[17],
                "SerumElectrolytesSodium": test_data[18],
                "SerumElectrolytesPotassium": test_data[19],
                "SerumElectrolytesCalcium": test_data[20],
                "SerumElectrolytesPhosphorus": test_data[21],
                "HemoglobinLevels": test_data[22],
                "Diuretics": test_data[23],
                "Edema": test_data[24],
                "FatigueLevels": test_data[25],
                "NauseaVomiting": test_data[26],
                "MuscleCramps": test_data[27],
                "Itching": test_data[28]
            }

            # Update the form fields with the test data
            for key, value in field_mapping.items():
                if key in self.input_widgets:
                    widget = self.input_widgets[key]
                    if isinstance(widget, ctk.CTkComboBox):
                        widget.set(value)
                    else:
                        widget.delete(0, tk.END)
                        widget.insert(0, str(value))

            messagebox.showinfo("تم التحميل", "تم تحميل بيانات الاختبار بنجاح")
        elif self.current_test_type == "LIVER SCAN":
            # ... existing liver scan test data code ...
            pass

    def show_detailed_diabetes_analysis(self, initial_data):
        # Create detailed diabetes analysis window
        diabetes_window = ctk.CTkToplevel(self.root)
        diabetes_window.title("تحليل السكري المفصل")
        diabetes_window.geometry("1100x750")
        diabetes_window.configure(fg_color="#f1f8e9")

        # Create main frame with scrollbar
        main_frame = ctk.CTkScrollableFrame(
            diabetes_window,
            fg_color="#f1f8e9",
            width=1000,
            height=700
        )
        main_frame.pack(expand=True, fill="both", padx=20, pady=20)

        # Header
        header_label = ctk.CTkLabel(
            main_frame,
            text="تحليل السكري المفصل",
            font=("Arial", 24, "bold"),
            text_color="#2e7d32"
        )
        header_label.pack(pady=10)

        # Create entry fields dictionary
        entries = {}
        
        # Define parameter groups with their fields
        parameter_groups = {
            "العوامل الوراثية والمناعية": {
                "Genetic Markers": ["إيجابي", "سلبي"],
                "Autoantibodies": ["إيجابي", "سلبي"],
                "Family History": ["نعم", "لا"],
                "Environmental Factors": ["موجود", "غير موجود"]
            },
            "القياسات السريرية": {
                "Insulin Levels": "entry",
                "Age": "entry",
                "BMI": "entry",
                "Blood Pressure": "entry",
                "Cholesterol Levels": "entry"
            },
            "نمط الحياة": {
                "Physical Activity": ["مرتفع", "متوسط", "منخفض"],
                "Dietary Habits": ["صحي", "غير صحي"],
                "Smoking Status": ["مدخن", "غير مدخن"],
                "Alcohol Consumption": ["مرتفع", "متوسط", "منخفض", "لا يوجد"]
            },
            "التاريخ الطبي": {
                "History of PCOS": ["نعم", "لا"],
                "Pregnancy History": ["طبيعي", "غير طبيعي", "لا ينطبق"],
            },
            "وظائف الأعضاء": {
                "Pancreatic Health": "entry",
                "Pulmonary Function": "entry",
                "Liver Function Tests": ["طبيعي", "غير طبيعي"],
                "Digestive Enzyme Levels": "entry"
            },
            "المؤشرات التشخيصية": {
                "Urine Test": ["وجود كيتونات", "طبيعي"],
                "Early Onset Symptoms": ["نعم", "لا"]
            }
        }

        # Create frames for each parameter group
        for group_name, parameters in parameter_groups.items():
            group_frame = ctk.CTkFrame(
                main_frame,
                fg_color="#e8f5e9",
                corner_radius=10
            )
            group_frame.pack(fill="x", padx=10, pady=5)

            # Group header
            group_label = ctk.CTkLabel(
                group_frame,
                text=group_name,
                font=("Arial", 16, "bold"),
                text_color="#1b5e20"
            )
            group_label.pack(pady=5)

            # Create parameter entries
            for param_name, param_type in parameters.items():
                param_frame = ctk.CTkFrame(
                    group_frame,
                    fg_color="#f1f8e9",
                    corner_radius=5
                )
                param_frame.pack(fill="x", padx=10, pady=2)

                # Parameter label
                label = ctk.CTkLabel(
                    param_frame,
                    text=param_name,
                    font=("Arial", 12),
                    text_color="#2e7d32"
                )
                label.pack(side="right", padx=10)

                # Create appropriate input widget based on parameter type
                if param_type == "entry":
                    entry = ctk.CTkEntry(
                        param_frame,
                        width=120,
                        height=30
                    )
                    entry.pack(side="left", padx=10)
                    entries[param_name] = entry
                    
                    # Set initial value if available
                    if param_name in initial_data:
                        entry.insert(0, str(initial_data[param_name]))
                else:
                    combobox = ctk.CTkComboBox(
                        param_frame,
                        values=param_type,
                        width=120,
                        height=30
                    )
                    combobox.pack(side="left", padx=10)
                    entries[param_name] = combobox
                    
                    # Set initial value if available
                    if param_name in initial_data:
                        value = str(initial_data[param_name])
                        if value in param_type:
                            combobox.set(value)

        # Create analyze button
        def analyze_data():
            # Collect data from entries
            data = {}
            for param_name, widget in entries.items():
                if isinstance(widget, ctk.CTkEntry):
                    data[param_name] = widget.get()
                else:  # ComboBox
                    data[param_name] = widget.get()
            
            # Perform analysis and show results
            risk_factors = self.analyze_risk_factors(data)
            
            # Create results window
            results_window = ctk.CTkToplevel(diabetes_window)
            results_window.title("نتائج التحليل")
            results_window.geometry("600x400")
            results_window.configure(fg_color="#f1f8e9")
            
            results_frame = ctk.CTkScrollableFrame(
                results_window,
                fg_color="#e8f5e9",
                width=550,
                height=350
            )
            results_frame.pack(expand=True, fill="both", padx=20, pady=20)
            
            # Display risk factors
            header = ctk.CTkLabel(
                results_frame,
                text="عوامل الخطورة المحددة",
                font=("Arial", 18, "bold"),
                text_color="#2e7d32"
            )
            header.pack(pady=10)
            
            for factor in risk_factors:
                factor_label = ctk.CTkLabel(
                    results_frame,
                    text=f"• {factor}",
                    font=("Arial", 12),
                    text_color="#1b5e20"
                )
                factor_label.pack(anchor="w", padx=10, pady=1)

        analyze_btn = ctk.CTkButton(
            main_frame,
            text="تحليل البيانات",
            font=("Arial", 14, "bold"),
            fg_color="#2e7d32",
            hover_color="#1b5e20",
            corner_radius=10,
            command=analyze_data
        )
        analyze_btn.pack(pady=20)

        # Set window position
        diabetes_window.update_idletasks()
        screen_width = diabetes_window.winfo_screenwidth()
        screen_height = diabetes_window.winfo_screenheight()
        x = (screen_width - 1100) // 2
        y = (screen_height - 750) // 2
        diabetes_window.geometry(f"1100x750+{x}+{y}")

    def get_risk_color(self, field, value):
        """Determine color based on risk level for different fields"""
        high_risk_color = "#d32f2f"  # Red
        moderate_risk_color = "#f57c00"  # Orange
        normal_color = "#2e7d32"  # Green
        
        # Numeric fields
        if field == "Insulin Levels":
            value = float(value)
            if value > 25: return high_risk_color
            if value < 3: return high_risk_color
            return normal_color
        elif field == "BMI":
            value = float(value)
            if value > 30: return high_risk_color
            if value > 25: return moderate_risk_color
            return normal_color
        elif field == "Blood Pressure":
            value = float(value)
            if value > 140: return high_risk_color
            if value > 120: return moderate_risk_color
            return normal_color
        elif field == "Cholesterol Levels":
            value = float(value)
            if value > 200: return high_risk_color
            if value > 170: return moderate_risk_color
            return normal_color
        
        # Categorical fields
        if value in ["Positive", "Yes", "Present", "Abnormal", "High", "Ketones Present"]:
            return high_risk_color
        if value in ["Moderate", "Borderline"]:
            return moderate_risk_color
        return normal_color

    def analyze_risk_factors(self, data):
        """Analyze risk factors and return list of concerns"""
        risk_factors = []
        
        # Genetic and autoimmune factors
        if data['Genetic Markers'] == 'Positive':
            risk_factors.append("Positive genetic markers indicate increased risk")
        if data['Autoantibodies'] == 'Positive':
            risk_factors.append("Presence of diabetes-related autoantibodies")
        if data['Family History'] == 'Yes':
            risk_factors.append("Family history of diabetes")
            
        # Lifestyle factors
        if data['Physical Activity'] == 'Low':
            risk_factors.append("Insufficient physical activity")
        if data['Dietary Habits'] == 'Unhealthy':
            risk_factors.append("Poor dietary habits")
        if data['Smoking Status'] == 'Smoker':
            risk_factors.append("Active smoking status")
        if data['Alcohol Consumption'] == 'High':
            risk_factors.append("High alcohol consumption")
            
        # Clinical measurements
        if float(data['Insulin Levels']) > 25:
            risk_factors.append("Elevated insulin levels")
        if float(data['BMI']) > 25:
            risk_factors.append("Elevated BMI")
        if float(data['Blood Pressure']) > 120:
            risk_factors.append("Elevated blood pressure")
        if float(data['Cholesterol Levels']) > 200:
            risk_factors.append("High cholesterol levels")
            
        # Other health factors
        if data['Liver Function Tests'] == 'Abnormal':
            risk_factors.append("Abnormal liver function")
        if data['Urine Test'] == 'Ketones Present':
            risk_factors.append("Presence of ketones in urine")
        if data['Early Onset Symptoms'] == 'Yes':
            risk_factors.append("Presence of early onset symptoms")
            
        return risk_factors

    def show_new_diagnostic_test(self, initial_data):
        # Create new test window
        new_test_window = ctk.CTkToplevel(self.root)
        new_test_window.title("Additional Analysis - Risk Factors")
        new_test_window.geometry("1100x750")
        new_test_window.configure(fg_color="#f1f8e9")

        main_frame = ctk.CTkScrollableFrame(
            new_test_window,
            fg_color="#f1f8e9",
            width=1000,
            height=700
        )
        main_frame.pack(expand=True, fill="both", padx=20, pady=20)

        header_label = ctk.CTkLabel(
            main_frame,
            text="Additional Analysis - Risk Factors",
            font=("Arial", 24, "bold"),
            text_color="#2e7d32"
        )
        header_label.pack(pady=10)

        # Required parameters for new test
        parameter_fields = [
            "Genetic Markers", "Autoantibodies", "Family History", "Environmental Factors",
            "Insulin Levels", "Age", "BMI", "Physical Activity", "Dietary Habits",
            "Blood Pressure", "Cholesterol Levels", "Smoking Status", "Alcohol Consumption",
            "History of PCOS", "Pregnancy History", "Pancreatic Health", "Pulmonary Function",
            "Liver Function Tests", "Digestive Enzyme Levels", "Urine Test", "Early Onset Symptoms"
        ]

        entries = {}

        # Create frames for different categories
        genetic_frame = ctk.CTkFrame(main_frame, fg_color="#e8f5e9", corner_radius=10)
        genetic_frame.pack(fill="x", padx=10, pady=5)
        ctk.CTkLabel(genetic_frame, text="Genetic and Autoimmune Factors", font=("Arial", 14, "bold"), text_color="#1b5e20").pack(pady=5)

        lifestyle_frame = ctk.CTkFrame(main_frame, fg_color="#e8f5e9", corner_radius=10)
        lifestyle_frame.pack(fill="x", padx=10, pady=5)
        ctk.CTkLabel(lifestyle_frame, text="Lifestyle and Environmental Factors", font=("Arial", 14, "bold"), text_color="#1b5e20").pack(pady=5)

        clinical_frame = ctk.CTkFrame(main_frame, fg_color="#e8f5e9", corner_radius=10)
        clinical_frame.pack(fill="x", padx=10, pady=5)
        ctk.CTkLabel(clinical_frame, text="Clinical Measurements", font=("Arial", 14, "bold"), text_color="#1b5e20").pack(pady=5)

        for field in parameter_fields:
            # Determine which frame to use
            if field in ["Genetic Markers", "Autoantibodies", "Family History"]:
                parent_frame = genetic_frame
            elif field in ["Physical Activity", "Dietary Habits", "Smoking Status", "Alcohol Consumption", "Environmental Factors"]:
                parent_frame = lifestyle_frame
            else:
                parent_frame = clinical_frame

            field_frame = ctk.CTkFrame(parent_frame, fg_color="#f1f8e9", corner_radius=10)
            field_frame.pack(fill="x", padx=10, pady=5)

            label = ctk.CTkLabel(
                field_frame, text=field, font=("Arial", 12), text_color="#2e7d32"
            )
            label.pack(side="right", padx=10)

            if field in ["Genetic Markers", "Autoantibodies", "Family History", "Environmental Factors",
                         "Physical Activity", "Dietary Habits", "Smoking Status", "Alcohol Consumption",
                         "History of PCOS", "Pregnancy History", "Liver Function Tests", "Urine Test",
                         "Early Onset Symptoms"]:
                values_map = {
                    "YesNo": ["Yes", "No"],
                    "PosNeg": ["Positive", "Negative"],
                    "Exist": ["Present", "Not Present"],
                    "Healthy": ["Healthy", "Unhealthy"],
                    "Level": ["High", "Medium", "Low", "None"],
                    "TestResult": ["Ketones Present", "Normal"],
                    "Pregnancy": ["Normal", "Abnormal", "Not Applicable"]
                }

                if field in ["Genetic Markers", "Autoantibodies"]:
                    values = values_map["PosNeg"]
                elif field in ["Environmental Factors"]:
                    values = values_map["Exist"]
                elif field in ["Dietary Habits"]:
                    values = values_map["Healthy"]
                elif field in ["Alcohol Consumption"]:
                    values = values_map["Level"]
                elif field in ["Urine Test"]:
                    values = values_map["TestResult"]
                elif field in ["Pregnancy History"]:
                    values = values_map["Pregnancy"]
                else:
                    values = values_map["YesNo"]

                combo = ctk.CTkComboBox(field_frame, values=values, width=120, height=30)
                combo.pack(side="left", padx=10)
                entries[field] = combo
            else:
                entry = ctk.CTkEntry(field_frame, width=120, height=30)
                entry.pack(side="left", padx=10)
                entries[field] = entry

        # Create results frame
        results_frame = ctk.CTkFrame(main_frame, fg_color="#e8f5e9", corner_radius=10)
        results_frame.pack(fill="x", padx=10, pady=10)

        result_label = ctk.CTkLabel(
            results_frame,
            text="Analysis Results Will Appear Here",
            font=("Arial", 16, "bold"),
            text_color="#2e7d32"
        )
        result_label.pack(pady=10)

        risk_factors_label = ctk.CTkLabel(
            results_frame,
            text="",
            font=("Arial", 12),
            text_color="#2e7d32"
        )
        risk_factors_label.pack(pady=5)

        def analyze():
            try:
                # Collect data from entries
                collected_data = {}
                for field, widget in entries.items():
                    value = widget.get()
                    # Convert categorical values to numeric
                    if field in ["Genetic Markers", "Autoantibodies"]:
                        collected_data[field] = 1 if value == "Positive" else 0
                    elif field in ["Family History", "Environmental Factors", "Physical Activity", 
                                 "Dietary Habits", "Smoking Status", "Alcohol Consumption", 
                                 "History of PCOS", "Pregnancy History", "Liver Function Tests", 
                                 "Urine Test", "Early Onset Symptoms"]:
                        if value in ["Yes", "Present", "Unhealthy", "High", "Abnormal", "Ketones Present"]:
                            collected_data[field] = 1
                        elif value in ["No", "Not Present", "Healthy", "Low", "Normal"]:
                            collected_data[field] = 0
                        else:
                            collected_data[field] = 0.5
                    else:
                        try:
                            collected_data[field] = float(value)
                        except ValueError:
                            messagebox.showerror("Error", f"Please enter a valid number for {field}")
                            return

                # Load the diabetes model
                model_path = "C:\\Users\\11\\Desktop\\work\\dr\\skry2.pkl"
                if os.path.exists(model_path):
                    diabetes_model = joblib.load(model_path)
                    
                    # Create DataFrame for prediction
                    test_data = pd.DataFrame([collected_data])
                    
                    # Make prediction
                    prediction = diabetes_model.predict(test_data)
                    prediction_proba = diabetes_model.predict_proba(test_data)
                    
                    # Analyze risk factors
                    risk_factors = self.analyze_risk_factors(collected_data)
                    
                    # Update result labels
                    result_text = f"Diabetes Type: {prediction[0]}\n"

                    if hasattr(diabetes_model, "predict_proba"):
                        result_text += f"Probability: {prediction_proba[0][1] * 100:.1f}%\n"

                    
                    if prediction_proba[0][1] >= 0.5:
                        risk_level = "High Risk - Immediate Medical Attention Required"
                        result_color = "#d32f2f"
                    else:
                        risk_level = "Moderate/Low Risk - Regular Monitoring Recommended"
                        result_color = "#2e7d32"
                        
                    result_text += f"\nRisk Level: {risk_level}"
                    result_label.configure(text=result_text, text_color=result_color)
                    
                    # Display risk factors
                    if risk_factors:
                        risk_factors_text = "Identified Risk Factors:\n• " + "\n• ".join(risk_factors)
                    else:
                        risk_factors_text = "No significant risk factors identified"
                    risk_factors_label.configure(text=risk_factors_text)
                    
                else:
                    messagebox.showerror("Error", "Diabetes model file not found!")
                    
            except Exception as e:
                messagebox.showerror("Error", f"An error occurred: {str(e)}")

        analyze_button = ctk.CTkButton(
            main_frame,
            text="Analyze Data",
            font=("Arial", 14, "bold"),
            fg_color="#2e7d32",
            hover_color="#1b5e20",
            corner_radius=10,
            command=analyze
        )
        analyze_button.pack(pady=20)

        # Center the window
        new_test_window.update_idletasks()
        screen_width = new_test_window.winfo_screenwidth()
        screen_height = new_test_window.winfo_screenheight()
        x = (screen_width - 1100) // 2
        y = (screen_height - 750) // 2
        new_test_window.geometry(f"1100x750+{x}+{y}")

if __name__ == "__main__":
    app = NeonHealthApp()
    app.run()