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
        self.diabetes_model_1 = None
        self.diabetes_model_2 = None
        
        # Load images
        try:
            self.pancreas_image = Image.open("assets/pancreas.png")
            self.pancreas_image = self.pancreas_image.resize((300, 300), Image.Resampling.LANCZOS)
            self.pancreas_photo = ImageTk.PhotoImage(self.pancreas_image)
        except Exception as e:
            print(f"Error loading pancreas image: {e}")
            self.pancreas_photo = None
        
        self.load_and_verify_models()
        self.show_login()

    def load_and_verify_models(self):
        """Load and verify all models needed for the application"""
        model_dir = os.path.join(os.path.dirname(os.path.abspath(__file__)))
        print(f"Looking for models in: {model_dir}")
        
        # Initialize model variables
        self.heart_model = None
        self.kidney_model = None
        self.liver_model = None
        self.anemia_model = None
        self.diabetes_model_1 = None
        self.diabetes_model_2 = None

        # Load images
        try:
            # Load heart model
            heart_model_path = os.path.join(model_dir, 'heart.pkl')
            if os.path.exists(heart_model_path):
                self.heart_model = joblib.load(heart_model_path)
                print("Heart model loaded successfully")
            else:
                print("Heart model file not found")
                self.heart_model = None
                
            # Load kidney model
            kidney_model_path = os.path.join(model_dir, 'KIDNEY.pkl')
            if os.path.exists(kidney_model_path):
                self.kidney_model = joblib.load(kidney_model_path)
                print("Kidney model loaded successfully")
            else:
                print("Kidney model file not found")
                self.kidney_model = None
                
            # Load liver model
            liver_model_path = os.path.join(model_dir, 'liver.pkl')
            if os.path.exists(liver_model_path):
                self.liver_model = joblib.load(liver_model_path)
                print("Liver model loaded successfully")
            else:
                print("Liver model file not found")
                self.liver_model = None
                
            # Load anemia model
            anemia_model_path = os.path.join(model_dir, 'anemia.pkl')
            if os.path.exists(anemia_model_path):
                self.anemia_model = joblib.load(anemia_model_path)
                print("Anemia model loaded successfully")
            else:
                print("Anemia model file not found")
                self.anemia_model = None

            # Load diabetes models
            diabetes_model_1_path = os.path.join(model_dir, 'diabetes_model_1.pkl')
            print(f"Looking for diabetes model 1 at: {diabetes_model_1_path}")
            if os.path.exists(diabetes_model_1_path):
                try:
                    self.diabetes_model_1 = joblib.load(diabetes_model_1_path)
                    print("Diabetes model 1 loaded successfully")
                except Exception as e:
                    print(f"Error loading diabetes model 1: {e}")
                    self.diabetes_model_1 = None
            else:
                print("Diabetes model 1 file not found")
                self.diabetes_model_1 = None

            diabetes_model_2_path = os.path.join(model_dir, 'diabetes_model_2.pkl')
            print(f"Looking for diabetes model 2 at: {diabetes_model_2_path}")
            if os.path.exists(diabetes_model_2_path):
                try:
                    self.diabetes_model_2 = joblib.load(diabetes_model_2_path)
                    print("Diabetes model 2 loaded successfully")
                except Exception as e:
                    print(f"Error loading diabetes model 2: {e}")
                    self.diabetes_model_2 = None
            else:
                print("Diabetes model 2 file not found")
                self.diabetes_model_2 = None

            # Print loaded models status
            print(f"Models loaded - Heart: {'Yes' if self.heart_model else 'No'}, " +
                  f"Kidney: {'Yes' if self.kidney_model else 'No'}, " +
                  f"Liver: {'Yes' if self.liver_model else 'No'}, " +
                  f"Anemia: {'Yes' if self.anemia_model else 'No'}, " +
                  f"Diabetes 1: {'Yes' if self.diabetes_model_1 else 'No'}, " +
                  f"Diabetes 2: {'Yes' if self.diabetes_model_2 else 'No'}")

            # Verify models with test data
            self.verify_models()
        except Exception as e:
            print(f"Error loading models: {str(e)}")
            messagebox.showerror("Model Error", f"Failed to load models: {str(e)}")
            return

    def verify_models(self):
        """Verify prediction models with test data"""
        # Load heart model
        try:
            model_path = os.path.join("C:\\Users\\11\\Desktop\\work\\dr", 'heart.pkl')
            self.heart_model = joblib.load(model_path)

            # Verify heart model with test data
            test_data = pd.DataFrame([[1, 45, 1, 15, 0, 0, 1, 0, 220, 140, 90, 25, 80, 100]],
                columns=['male', 'age', 'currentSmoker', 'cigsPerDay', 'BPMeds',
                        'prevalentStroke', 'prevalentHyp', 'diabetes', 'totChol',
                        'sysBP', 'diaBP', 'BMI', 'heartRate', 'glucose'])

            try:
                prediction = self.heart_model.predict(test_data)
                prediction_proba = self.heart_model.predict_proba(test_data)
                print("Heart model loaded and verified successfully")
            except Exception as e:
                print(f"Heart model verification failed: {str(e)}")
                self.heart_model = None

        except Exception as e:
            print(f"Error loading heart model: {str(e)}")
            self.heart_model = None
            messagebox.showerror("Model Error",
                "Failed to load heart model. Some features may be unavailable.")

        # Load kidney model
        try:
            model_path = os.path.join("C:\\Users\\11\\Desktop\\work\\dr", 'KIDNEY.pkl')
            self.kidney_model = joblib.load(model_path)

            # Verify kidney model with test data
            test_data = pd.DataFrame([[45, 1, 28, 1, 3, 0, 1, 0, 0, 120, 80, 95, 5.4, 1.2, 14, 75, 0.2, 3.5, 138, 4.5, 2.3, 3.9, 15.0, 0, 0, 6, 0, 0, 0]],
                columns=['Age', 'Gender', 'BMI', 'Smoking', 'AlcoholConsumption',
                       'FamilyHistoryKidneyDisease', 'FamilyHistoryHypertension',
                       'FamilyHistoryDiabetes', 'PreviousAcuteKidneyInjury',
                       'SystolicBP', 'DiastolicBP', 'FastingBloodSugar', 'HbA1c',
                       'SerumCreatinine', 'BUNLevels', 'GFR', 'ProteinInUrine',
                       'ACR', 'SerumElectrolytesSodium', 'SerumElectrolytesPotassium',
                       'SerumElectrolytesCalcium', 'SerumElectrolytesPhosphorus',
                       'HemoglobinLevels', 'Diuretics', 'Edema', 'FatigueLevels',
                       'NauseaVomiting', 'MuscleCramps', 'Itching'])

            try:
                prediction = self.kidney_model.predict(test_data)
                prediction_proba = self.kidney_model.predict_proba(test_data)
                print("Kidney model loaded and verified successfully")
            except Exception as e:
                print(f"Kidney model verification failed: {str(e)}")
                self.kidney_model = None

        except Exception as e:
            print(f"Error loading kidney model: {str(e)}")
            self.kidney_model = None
            messagebox.showerror("Model Error",
                "Failed to load kidney model. Some features may be unavailable.")

        # Load liver model
        try:
            model_path = os.path.join("C:\\Users\\11\\Desktop\\work\\dr", 'liver.pkl')
            self.liver_model = joblib.load(model_path)

            # Verify liver model with test data
            test_data = pd.DataFrame([[45, 0, 0.7, 0.1, 187, 16, 18, 6.8, 3.3, 0.9]],
                columns=['age', 'gender', 'total_bilirubin', 'direct_bilirubin',
                        'alkphos', 'sgpt', 'sgot', 'total_proteins', 'albumin', 'ag_ratio'])

            try:
                prediction = self.liver_model.predict(test_data)
                prediction_proba = self.liver_model.predict_proba(test_data)
                print("Liver model loaded and verified successfully")
            except Exception as e:
                print(f"Liver model verification failed: {str(e)}")
                self.liver_model = None

        except Exception as e:
            print(f"Error loading liver model: {str(e)}")
            self.liver_model = None
            messagebox.showerror("Model Error",
                "Failed to load liver model. Some features may be unavailable.")

        # Load anemia model
        try:
            model_path = os.path.join("C:\\Users\\11\\Desktop\\work\\dr", 'anemia.pkl')
            self.anemia_model = joblib.load(model_path)

            # Verify anemia model with test data
            test_data = pd.DataFrame([[3.1, 15, 80, 0.8, 6.5, 3.2, 8.5, 28, 65, 22, 29, 420, 19.5, 0.45]],
                columns=['WBC', 'LYMp', 'NEUTp', 'LYMn', 'NEUTn', 'RBC', 'HGB',
                        'HCT', 'MCV', 'MCH', 'MCHC', 'PLT', 'PDW', 'PCT'])

            try:
                prediction = self.anemia_model.predict(test_data)
                prediction_proba = self.anemia_model.predict_proba(test_data)
                print("Anemia model loaded and verified successfully")
            except Exception as e:
                print(f"Anemia model verification failed: {str(e)}")
                self.anemia_model = None

        except Exception as e:
            print(f"Error loading anemia model: {str(e)}")
            self.anemia_model = None
            messagebox.showerror("Model Error",
                "Failed to load anemia model. Some features may be unavailable.")

        # Load diabetes models
        try:
            model_path = os.path.join("C:\\Users\\11\\Desktop\\work\\dr", 'diabetes_model_1.pkl')
            self.diabetes_model_1 = joblib.load(model_path)

            # Verify diabetes model 1 with test data
            test_data = pd.DataFrame([[
                0.121786313, 0.023058437, 0.94489324, 0.905372145, 0.507710985, 
                0.40303319, 0.164216445, 0.307553206, 0.207938382, 0.505561858, 
                0.571161502, 0.839270508, 0.580902575, 0.556037486, 0.47774212, 
                0.856809908, 0.652465332, 0.106960917, 0.94254879, 0.344260902, 
                0.66636811, 0.659059785, 0.816982046, 0.401165962
            ]],
            columns=[
                'Glucose', 'Cholesterol', 'Hemoglobin', 'Platelets', 'WhiteBloodCells',
                'RedBloodCells', 'Hematocrit', 'MeanCorpuscularVolume', 'MeanCorpuscularHemoglobin',
                'MeanCorpuscularHemoglobinConcentration', 'Insulin', 'BMI', 'SystolicBP', 'DiastolicBP',
                'Triglycerides', 'HbA1c', 'LDLCholesterol', 'HDLCholesterol', 'ALT', 'AST',
                'HeartRate', 'Creatinine', 'Troponin', 'CReactiveProtein'
            ])

            try:
                prediction = self.diabetes_model_1.predict(test_data)
                prediction_proba = self.diabetes_model_1.predict_proba(test_data)
                print("Diabetes model 1 loaded and verified successfully")
            except Exception as e:
                print(f"Diabetes model 1 verification failed: {str(e)}")
                self.diabetes_model_1 = None

        except Exception as e:
            print(f"Error loading diabetes model 1: {str(e)}")
            self.diabetes_model_1 = None
            messagebox.showerror("Model Error",
                "Failed to load diabetes model 1. Some features may be unavailable.")

        try:
            model_path = os.path.join("C:\\Users\\11\\Desktop\\work\\dr", 'diabetes_model_2.pkl')
            self.diabetes_model_2 = joblib.load(model_path)

            # Verify diabetes model 2 with test data
            test_data = pd.DataFrame([[
                0.121786313, 0.023058437, 0.94489324, 0.905372145, 0.507710985, 
                0.40303319, 0.164216445, 0.307553206, 0.207938382, 0.505561858, 
                0.571161502, 0.839270508, 0.580902575, 0.556037486, 0.47774212, 
                0.856809908, 0.652465332, 0.106960917, 0.94254879, 0.344260902, 
                0.66636811, 0.659059785, 0.816982046, 0.401165962
            ]],
            columns=[
                'Glucose', 'Cholesterol', 'Hemoglobin', 'Platelets', 'WhiteBloodCells',
                'RedBloodCells', 'Hematocrit', 'MeanCorpuscularVolume', 'MeanCorpuscularHemoglobin',
                'MeanCorpuscularHemoglobinConcentration', 'Insulin', 'BMI', 'SystolicBP', 'DiastolicBP',
                'Triglycerides', 'HbA1c', 'LDLCholesterol', 'HDLCholesterol', 'ALT', 'AST',
                'HeartRate', 'Creatinine', 'Troponin', 'CReactiveProtein'
            ])

            try:
                prediction = self.diabetes_model_2.predict(test_data)
                prediction_proba = self.diabetes_model_2.predict_proba(test_data)
                print("Diabetes model 2 loaded and verified successfully")
            except Exception as e:
                print(f"Diabetes model 2 verification failed: {str(e)}")
                self.diabetes_model_2 = None

        except Exception as e:
            print(f"Error loading diabetes model 2: {str(e)}")
            self.diabetes_model_2 = None
            messagebox.showerror("Model Error",
                "Failed to load diabetes model 2. Some features may be unavailable.")

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
            # Create DataFrame
            df = pd.DataFrame([data])
            
            # Verify all required columns are present
            required_columns = [
                # Basic Demographics
                'Age', 'Gender', 'BMI',
                # Lifestyle and History
                'Smoking', 'AlcoholConsumption', 'FamilyHistoryKidneyDisease',
                'FamilyHistoryHypertension', 'FamilyHistoryDiabetes', 'PreviousAcuteKidneyInjury',
                # Vital Signs
                'SystolicBP', 'DiastolicBP',
                # Blood Tests
                'FastingBloodSugar', 'HbA1c', 'SerumCreatinine', 'BUNLevels',
                'GFR', 'HemoglobinLevels',
                # Electrolytes
                'SerumElectrolytesSodium', 'SerumElectrolytesPotassium',
                'SerumElectrolytesCalcium', 'SerumElectrolytesPhosphorus',
                # Urine Tests
                'ProteinInUrine', 'ACR',
                # Medications
                'Diuretics',
                # Symptoms
                'Edema', 'FatigueLevels', 'NauseaVomiting', 'MuscleCramps', 'Itching'
            ]
            
            for col in required_columns:
                if col not in df.columns:
                    raise ValueError(f"Missing required parameter: {col}")
            
            # Ensure correct data types and ranges
            validations = {
                'Age': (0, 120),                          # Age in years
                'Gender': (0, 1),                         # Gender (0 or 1)
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
                'HemoglobinLevels': (3, 20),             # Hemoglobin in g/dL
                'SerumElectrolytesSodium': (110, 180),   # Sodium in mEq/L
                'SerumElectrolytesPotassium': (2, 8),    # Potassium in mEq/L
                'SerumElectrolytesCalcium': (2, 15),     # Calcium in mg/dL
                'SerumElectrolytesPhosphorus': (1, 10),  # Phosphorus in mg/dL
                'ACR': (0, 5000),                        # ACR in mg/g
                'Diuretics': (0, 1),                     # 0 for No, 1 for Yes
                'Edema': (0, 1),                         # 0 for No, 1 for Yes
                'FatigueLevels': (1, 10),                # Fatigue scale 1-10
                'NauseaVomiting': (0, 1),                # 0 for No, 1 for Yes
                'MuscleCramps': (0, 1),                  # 0 for No, 1 for Yes
                'Itching': (0, 1)                        # 0 for No, 1 for Yes
            }
            
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
        """
        Process and analyze diabetes data using the diabetes classification model
        """
        try:
            # Convert input data to float values
            processed_data = {}
            for key, value in data.items():
                if key in ['Gender', 'Smoking']:
                    processed_data[key] = 1 if value == 'Male' or value == 'Yes' else 0
                else:
                    processed_data[key] = float(value)
            
            # Analyze the data
            # (Add your diabetes analysis logic here)
            diabetes_result = {
                'is_at_risk': True,
                'risk_probability': 0.75,
                'risk_factors': []
            }
            
            # Analyze risk factors
            if processed_data['Glucose'] > 126:
                diabetes_result['risk_factors'].append('High Fasting Glucose')
            if processed_data['HbA1c'] > 6.5:
                diabetes_result['risk_factors'].append('Elevated HbA1c')
            
            return diabetes_result
        except Exception as e:
            raise Exception(f"Error processing diabetes data: {str(e)}")
    
    def process_diabetes_data_model1(self, data):
        """
        Process diabetes data for model 1 - prepares data in the format expected by the model
        """
        try:
            print("Processing diabetes data for model 1")
            
            # Convert all values to float
            processed_data = {}
            for key, value in data.items():
                try:
                    processed_data[key] = float(value)
                except ValueError:
                    print(f"Warning: Could not convert {key}={value} to float")
                    processed_data[key] = 0.0
            
            # Create DataFrame with required columns in the correct order
            # Use the same column names and order that the model was trained with
            df = pd.DataFrame({
                'Glucose': [processed_data.get('Glucose', 0.0)],
                'Cholesterol': [processed_data.get('Cholesterol', 0.0)],
                'Hemoglobin': [processed_data.get('Hemoglobin', 0.0)],
                'Platelets': [processed_data.get('Platelets', 0.0)],
                'WhiteBloodCells': [processed_data.get('WhiteBloodCells', 0.0)],
                'RedBloodCells': [processed_data.get('RedBloodCells', 0.0)],
                'Hematocrit': [processed_data.get('Hematocrit', 0.0)],
                'MeanCorpuscularVolume': [processed_data.get('MeanCorpuscularVolume', 0.0)],
                'MeanCorpuscularHemoglobin': [processed_data.get('MeanCorpuscularHemoglobin', 0.0)],
                'MeanCorpuscularHemoglobinConcentration': [processed_data.get('MeanCorpuscularHemoglobinConcentration', 0.0)],
                'Insulin': [processed_data.get('Insulin', 0.0)],
                'BMI': [processed_data.get('BMI', 0.0)],
                'SystolicBP': [processed_data.get('SystolicBP', 0.0)],
                'DiastolicBP': [processed_data.get('DiastolicBP', 0.0)],
                'Triglycerides': [processed_data.get('Triglycerides', 0.0)],
                'HbA1c': [processed_data.get('HbA1c', 0.0)],
                'LDLCholesterol': [processed_data.get('LDLCholesterol', 0.0)],
                'HDLCholesterol': [processed_data.get('HDLCholesterol', 0.0)],
                'ALT': [processed_data.get('ALT', 0.0)],
                'AST': [processed_data.get('AST', 0.0)],
                'HeartRate': [processed_data.get('HeartRate', 0.0)],
                'Creatinine': [processed_data.get('Creatinine', 0.0)],
                'Troponin': [processed_data.get('Troponin', 0.0)],
                'CReactiveProtein': [processed_data.get('CReactiveProtein', 0.0)]
            })
            
            print(f"Processed data for diabetes model 1: {df.shape}")
            print(f"Columns: {df.columns.tolist()}")
            
            return df
            
        except Exception as e:
            print(f"Error in process_diabetes_data_model1: {str(e)}")
            raise Exception(f"Error processing diabetes data for model 1: {str(e)}")
    
    def process_diabetes_data_model2(self, data):
        """
        Process diabetes data for model 2 - prepares data in the format expected by the model
        """
        try:
            print("Processing diabetes data for model 2")
            
            # Convert all values to float
            processed_data = {}
            for key, value in data.items():
                try:
                    processed_data[key] = float(value)
                except ValueError:
                    print(f"Warning: Could not convert {key}={value} to float")
                    processed_data[key] = 0.0
            
            # Create DataFrame with required columns in the correct order
            # Use the same column names and order that the model was trained with
            df = pd.DataFrame({
                'Glucose': [processed_data.get('Glucose', 0.0)],
                'Cholesterol': [processed_data.get('Cholesterol', 0.0)],
                'Hemoglobin': [processed_data.get('Hemoglobin', 0.0)],
                'Platelets': [processed_data.get('Platelets', 0.0)],
                'WhiteBloodCells': [processed_data.get('WhiteBloodCells', 0.0)],
                'RedBloodCells': [processed_data.get('RedBloodCells', 0.0)],
                'Hematocrit': [processed_data.get('Hematocrit', 0.0)],
                'MeanCorpuscularVolume': [processed_data.get('MeanCorpuscularVolume', 0.0)],
                'MeanCorpuscularHemoglobin': [processed_data.get('MeanCorpuscularHemoglobin', 0.0)],
                'MeanCorpuscularHemoglobinConcentration': [processed_data.get('MeanCorpuscularHemoglobinConcentration', 0.0)],
                'Insulin': [processed_data.get('Insulin', 0.0)],
                'BMI': [processed_data.get('BMI', 0.0)],
                'SystolicBP': [processed_data.get('SystolicBP', 0.0)],
                'DiastolicBP': [processed_data.get('DiastolicBP', 0.0)],
                'Triglycerides': [processed_data.get('Triglycerides', 0.0)],
                'HbA1c': [processed_data.get('HbA1c', 0.0)],
                'LDLCholesterol': [processed_data.get('LDLCholesterol', 0.0)],
                'HDLCholesterol': [processed_data.get('HDLCholesterol', 0.0)],
                'ALT': [processed_data.get('ALT', 0.0)],
                'AST': [processed_data.get('AST', 0.0)],
                'HeartRate': [processed_data.get('HeartRate', 0.0)],
                'Creatinine': [processed_data.get('Creatinine', 0.0)],
                'Troponin': [processed_data.get('Troponin', 0.0)],
                'CReactiveProtein': [processed_data.get('CReactiveProtein', 0.0)]
            })
            
            print(f"Processed data for diabetes model 2: {df.shape}")
            print(f"Columns: {df.columns.tolist()}")
            
            return df
            
        except Exception as e:
            print(f"Error in process_diabetes_data_model2: {str(e)}")
            raise Exception(f"Error processing diabetes data for model 2: {str(e)}")
            
    def validate_and_analyze(self, test_type, data):
        """Validate inputs and perform analysis"""
        try:
            print(f"Starting analysis for {test_type}")
            print(f"Input data: {data}")
            
            if test_type == "ANEMIA ANALYSIS":
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
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                print(f"Stored prediction results: {self.prediction_results}")
                
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
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                print(f"Stored prediction results: {self.prediction_results}")
                
                return True
                
            elif test_type == "QUANTUM KIDNEY ANALYSIS":
                if self.kidney_model is None:
                    print("Error: Kidney model is not loaded")
                    raise ValueError("Kidney disease prediction model is not available")
                
                print("Processing kidney data...")
                # Process and validate data
                df = self.process_kidney_data(data)
                print(f"Processed DataFrame: {df}")
                
                # Make prediction
                print("Making prediction...")
                prediction = self.kidney_model.predict(df)
                prediction_proba = self.kidney_model.predict_proba(df)
                print(f"Prediction: {prediction}")
                print(f"Prediction probability: {prediction_proba}")
                
                # Calculate risk factors
                print("Calculating risk factors...")
                risk_factors = []
                
                try:
                    if float(data['Age']) > 60: risk_factors.append("Advanced age")
                    if data['Smoking'] == 'Yes': risk_factors.append("Current smoker")
                    if float(data['SystolicBP']) > 140 or float(data['DiastolicBP']) > 90: risk_factors.append("High blood pressure")
                    if float(data['BMI']) > 30: risk_factors.append("Obesity")
                    if float(data['FastingBloodSugar']) > 126: risk_factors.append("High blood sugar")
                    if float(data['HbA1c']) > 6.5: risk_factors.append("Elevated HbA1c")
                    if float(data['SerumCreatinine']) > 1.2: risk_factors.append("Elevated creatinine")
                    if float(data['GFR']) < 60: risk_factors.append("Reduced GFR")
                    if float(data['ProteinInUrine']) > 30: risk_factors.append("Proteinuria")
                    if float(data['ACR']) > 30: risk_factors.append("Elevated ACR")
                    if data['FamilyHistoryKidneyDisease'] == 'Yes': risk_factors.append("Family history of kidney disease")
                    if data['PreviousAcuteKidneyInjury'] == 'Yes': risk_factors.append("History of acute kidney injury")
                    if float(data['HemoglobinLevels']) < 12: risk_factors.append("Low hemoglobin")
                    
                    # Check electrolyte imbalances
                    if float(data['SerumElectrolytesSodium']) < 135 or float(data['SerumElectrolytesSodium']) > 145:
                        risk_factors.append("Sodium imbalance")
                    if float(data['SerumElectrolytesPotassium']) < 3.5 or float(data['SerumElectrolytesPotassium']) > 5.0:
                        risk_factors.append("Potassium imbalance")
                    if float(data['SerumElectrolytesCalcium']) < 8.5 or float(data['SerumElectrolytesCalcium']) > 10.5:
                        risk_factors.append("Calcium imbalance")
                    if float(data['SerumElectrolytesPhosphorus']) > 4.5:
                        risk_factors.append("Elevated phosphorus")
                    
                    # Check symptoms
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
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors
                }
                
                return True
                
            elif test_type == "DIABETES PREDICTION":
                if self.diabetes_model_1 is None:
                    raise ValueError("Primary diabetes screening model is not available")
                
                # Process and validate data for Model 1
                df = self.process_diabetes_data_model1(data)
                model = self.diabetes_model_1
                
                # Make prediction
                prediction = model.predict(df)
                prediction_proba = model.predict_proba(df)
                
                # Calculate risk factors based on model type
                risk_factors = []
                
                # Risk factors for Model 1
                if float(data['Glucose']) > 126: risk_factors.append("Elevated blood glucose")
                if float(data['HbA1c']) > 6.5: risk_factors.append("Elevated HbA1c")
                if float(data['Insulin']) > 25: risk_factors.append("Elevated insulin")
                if float(data['BMI']) > 30: risk_factors.append("Obesity")
                if float(data['Triglycerides']) > 150: risk_factors.append("High triglycerides")
                if float(data['HDLCholesterol']) < 40: risk_factors.append("Low HDL cholesterol")
                if float(data['SystolicBP']) > 140: risk_factors.append("High blood pressure")
                
                # Store results
                self.prediction_results = {
                    "prediction": int(prediction[0]),
                    "probability": prediction_proba[0][1],
                    "input_data": data,
                    "risk_factors": risk_factors,
                    "model_type": 1
                }
                
                # If Model 1 indicates diabetes, pass data to Model 2 for further analysis
                if int(prediction[0]) == 1:
                    if self.diabetes_model_2 is None:
                        raise ValueError("Secondary diabetes analysis model is not available")
                    
                    # Process and validate data for Model 2
                    df = self.process_diabetes_data_model2(data)
                    model = self.diabetes_model_2
                    
                    # Make prediction
                    prediction = model.predict(df)
                    prediction_proba = model.predict_proba(df)
                    
                    # Calculate risk factors based on model type
                    risk_factors = []
                    
                    # Risk factors for Model 2
                    if data['FamilyHistory'] != 'None': risk_factors.append("Family history of diabetes")
                    if data['PhysicalActivity'] == 'Sedentary': risk_factors.append("Sedentary lifestyle")
                    if data['DietaryHabits'] == 'Poor': risk_factors.append("Poor dietary habits")
                    if data['SmokingStatus'] == 'Current': risk_factors.append("Current smoker")
                    if float(data['BMI']) > 30: risk_factors.append("Obesity")
                    if float(data['BloodPressure']) > 140: risk_factors.append("High blood pressure")
                    if data['PCOSHistory'] == 'Yes': risk_factors.append("History of PCOS")
                    if data['EarlyOnsetSymptoms'] != 'None': risk_factors.append("Early onset symptoms present")
                    
                    # Store results
                    self.prediction_results = {
                        "prediction": int(prediction[0]),
                        "probability": prediction_proba[0][1],
                        "input_data": data,
                        "risk_factors": risk_factors,
                        "model_type": 2
                    }
                
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
            fg_color="black",
            width=2,
            corner_radius=0
        )
        separator.pack(side="left", fill="y")
        
        # Add logout button
        logout_frame = ctk.CTkFrame(
            sidebar,
            fg_color="black",
            corner_radius=20,
            height=50
        )
        logout_frame.pack(pady=(20, 10), padx=20, fill="x")
        
        logout_button = ctk.CTkButton(
            logout_frame,
            text="🚪 Logout",
            font=("Arial Black", 14),
            fg_color="transparent",
            hover_color="#333333",
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
            fg_color="black",
            corner_radius=20,
            height=50
        )
        search_frame.pack(pady=(10, 30), padx=20, fill="x")
        
        search_button = ctk.CTkButton(
            search_frame,
            text="🔍 Search",
            font=("Arial Black", 14),
            fg_color="transparent",
            hover_color="#333333",
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
                fg_color="#00CD66",
                corner_radius=15,
                height=45
            )
            button_frame.pack(pady=5, padx=20, fill="x")
            
            button = ctk.CTkButton(
                button_frame,
                text=text,
                font=("Arial", 14),
                fg_color="transparent",
                text_color="black",
                hover_color="#00B359",
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
        for i in range(5):
            buttons_container.grid_columnconfigure(i, weight=1)

        # Navigation buttons with their corresponding test types
        nav_buttons = [
            ("Kidney", "QUANTUM KIDNEY ANALYSIS"),
            ("Heart", "CARDIAC HOLOGRAM"),
            ("Liver", "LIVER SCAN"),
            ("Diabetes", "DIABETES PREDICTION"),
            ("Anemia", "ANEMIA ANALYSIS")
        ]
        
        for i, (button_text, test_type) in enumerate(nav_buttons):
            button = ctk.CTkButton(
                buttons_container,
                text=button_text,
                width=180,  # Increased from 120 to 180
                height=50,  # Increased from 40 to 50
                corner_radius=25,  # Increased from 20 to 25
                font=("Arial", 16, "bold"),  # Increased font size from 14 to 16
                fg_color="#00CD66",
                hover_color="#00B359",
                text_color="black",
                border_width=2,
                border_color="black",
                command=lambda t=test_type: self.show_test_details(t)
            )
            button.grid(row=0, column=i, padx=15, pady=15)  # Adjusted padding
        
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
                    "name": "NEURAL DIABETES SCAN",
                    "position": (380, 500),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\3.png"
                },
                {
                    "name": "QUANTUM KIDNEY ANALYSIS",
                    "position": (450, 485),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\2.png"
                },
                {
                    "name": "ANEMIA ANALYSIS",
                    "position": (245, 450),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\5.png"
                },
                {
                    "name": "LIVER SCAN",
                    "position": (390, 450),
                    "size": (60, 60),
                    "hover_size": (90, 90),
                    "image_path": r"C:\Users\11\Desktop\work\dr\x\4.png"
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

    def on_button_hover(self, event, canvas, button_index):
        """Handle mouse hover over button"""
        canvas.itemconfig(f"button_{button_index}", image=self.button_images_large[button_index])

    def on_button_leave(self, event, canvas, button_index):
        """Handle mouse leave from button"""
        canvas.itemconfig(f"button_{button_index}", image=self.button_images[button_index])

    def show_test_details(self, test_type):
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
        
        def load_test_data():
            print("Loading test data...")
            if test_type == "LIVER SCAN":
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
            elif test_type == "QUANTUM KIDNEY ANALYSIS":
                test_data = {
                    "Age": "45",
                    "Gender": "Male",
                    "BMI": "28",
                    "Smoking": "Yes",
                    "AlcoholConsumption": "3",
                    "FamilyHistoryKidneyDisease": "No",
                    "FamilyHistoryHypertension": "Yes",
                    "FamilyHistoryDiabetes": "No",
                    "PreviousAcuteKidneyInjury": "No",
                    "SystolicBP": "120",
                    "DiastolicBP": "80",
                    "FastingBloodSugar": "95",
                    "HbA1c": "5.4",
                    "SerumCreatinine": "1.2",
                    "BUNLevels": "14",
                    "GFR": "75",
                    "ProteinInUrine": "0.2",
                    "ACR": "3.5",
                    "SerumElectrolytesSodium": "138",
                    "SerumElectrolytesPotassium": "4.5",
                    "SerumElectrolytesCalcium": "2.3",
                    "SerumElectrolytesPhosphorus": "3.9",
                    "HemoglobinLevels": "15.0",
                    "Diuretics": "No",
                    "Edema": "No",
                    "FatigueLevels": "6",
                    "NauseaVomiting": "No",
                    "MuscleCramps": "No",
                    "Itching": "No"
                }
            elif test_type == "CARDIAC HOLOGRAM":
                test_data = {
                    "male": "Male",
                    "age": "45",
                    "currentSmoker": "Yes",
                    "cigsPerDay": "15",
                    "BPMeds": "No",
                    "prevalentStroke": "No",
                    "prevalentHyp": "No",
                    "diabetes": "No",
                    "totChol": "220",
                    "sysBP": "140",
                    "diaBP": "90",
                    "BMI": "25",
                    "heartRate": "80",
                    "glucose": "100"
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
            elif test_type == "DIABETES PREDICTION":
                test_data = {
                    'Glucose': 0.121786313,
                    'Cholesterol': 0.023058437,
                    'Hemoglobin': 0.94489324,
                    'Platelets': 0.905372145,
                    'WhiteBloodCells': 0.507710985,
                    'RedBloodCells': 0.40303319,
                    'Hematocrit': 0.164216445,
                    'MeanCorpuscularVolume': 0.307553206,
                    'MeanCorpuscularHemoglobin': 0.207938382,
                    'MeanCorpuscularHemoglobinConcentration': 0.505561858,
                    'Insulin': 0.571161502,
                    'BMI': 0.839270508,
                    'SystolicBP': 0.580902575,
                    'DiastolicBP': 0.556037486,
                    'Triglycerides': 0.47774212,
                    'HbA1c': 0.856809908,
                    'LDLCholesterol': 0.652465332,
                    'HDLCholesterol': 0.106960917,
                    'ALT': 0.94254879,
                    'AST': 0.344260902,
                    'HeartRate': 0.66636811,
                    'Creatinine': 0.659059785,
                    'Troponin': 0.816982046,
                    'CReactiveProtein': 0.401165962
                }
            else:
                return
            
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
        if test_type in ["QUANTUM KIDNEY ANALYSIS", "CARDIAC HOLOGRAM", "LIVER SCAN", "ANEMIA ANALYSIS", "DIABETES PREDICTION"]:
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
        scrollable_frame = ctk.CTkScrollableFrame(
            container,
            fg_color="#f1f8e9",
            corner_radius=20,
            border_width=1,
            border_color="#558b2f",
            height=400
        )
        scrollable_frame.pack(pady=20, padx=40, fill="x")
        
        # Dictionary of parameters for each test type
        params = {
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
            "QUANTUM KIDNEY ANALYSIS": [
                # Basic Demographics
                {"name": "Age", "key": "Age", "type": "number", "placeholder": "Enter age"},
                {"name": "Gender (1 for Male, 0 for Female)", "key": "Gender", "type": "number", "placeholder": "Enter 1 for Male, 0 for Female"},
                {"name": "BMI", "key": "BMI", "type": "number", "placeholder": "Enter BMI"},
                
                # Lifestyle and History
                {"name": "Smoking", "key": "Smoking", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                {"name": "Alcohol Consumption", "key": "AlcoholConsumption", "type": "number", "placeholder": "Units per week"},
                {"name": "Family History of Kidney Disease", "key": "FamilyHistoryKidneyDisease", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                {"name": "Family History of Hypertension", "key": "FamilyHistoryHypertension", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                {"name": "Family History of Diabetes", "key": "FamilyHistoryDiabetes", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                {"name": "Previous Acute Kidney Injury", "key": "PreviousAcuteKidneyInjury", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                
                # Vital Signs
                {"name": "Systolic Blood Pressure", "key": "SystolicBP", "type": "number", "placeholder": "Enter systolic BP"},
                {"name": "Diastolic Blood Pressure", "key": "DiastolicBP", "type": "number", "placeholder": "Enter diastolic BP"},
                
                # Blood Tests
                {"name": "Fasting Blood Sugar", "key": "FastingBloodSugar", "type": "number", "placeholder": "Enter fasting blood sugar"},
                {"name": "HbA1c Level", "key": "HbA1c", "type": "number", "placeholder": "Enter HbA1c level"},
                {"name": "Serum Creatinine", "key": "SerumCreatinine", "type": "number", "placeholder": "Enter serum creatinine"},
                {"name": "BUN Levels", "key": "BUNLevels", "type": "number", "placeholder": "Enter BUN levels"},
                {"name": "GFR", "key": "GFR", "type": "number", "placeholder": "Enter GFR value"},
                {"name": "Hemoglobin Levels", "key": "HemoglobinLevels", "type": "number", "placeholder": "Enter hemoglobin level"},
                
                # Electrolytes
                {"name": "Serum Sodium", "key": "SerumElectrolytesSodium", "type": "number", "placeholder": "Enter sodium level"},
                {"name": "Serum Potassium", "key": "SerumElectrolytesPotassium", "type": "number", "placeholder": "Enter potassium level"},
                {"name": "Serum Calcium", "key": "SerumElectrolytesCalcium", "type": "number", "placeholder": "Enter calcium level"},
                {"name": "Serum Phosphorus", "key": "SerumElectrolytesPhosphorus", "type": "number", "placeholder": "Enter phosphorus level"},
                
                # Urine Tests
                {"name": "Protein in Urine", "key": "ProteinInUrine", "type": "number", "placeholder": "Enter protein in urine"},
                {"name": "ACR", "key": "ACR", "type": "number", "placeholder": "Enter albumin-to-creatinine ratio"},
                
                # Medications
                {"name": "Diuretics Usage", "key": "Diuretics", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                
                # Symptoms
                {"name": "Edema", "key": "Edema", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                {"name": "Fatigue Level", "key": "FatigueLevels", "type": "number", "placeholder": "Enter fatigue level (1-10)"},
                {"name": "Nausea/Vomiting", "key": "NauseaVomiting", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                {"name": "Muscle Cramps", "key": "MuscleCramps", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"},
                {"name": "Itching", "key": "Itching", "type": "number", "placeholder": "Enter 1 for Yes, 0 for No"}
            ],
            "LIVER SCAN": [
                # Demographics
                {"name": "Age", "key": "age", "type": "number", "placeholder": "Enter age"},
                {"name": "Gender", "key": "gender", "type": "combobox", "values": ["Male", "Female"], "placeholder": "Select gender"},
                
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
            "NEURAL DIABETES SCAN": ["Glucose Level", "Blood Pressure", "Insulin", "BMI"],
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
            ],
            "DIABETES PREDICTION": [
                {"name": "Glucose", "key": "Glucose", "type": "number", "placeholder": "Enter Glucose level"},
                {"name": "Cholesterol", "key": "Cholesterol", "type": "number", "placeholder": "Enter Cholesterol level"},
                {"name": "Hemoglobin", "key": "Hemoglobin", "type": "number", "placeholder": "Enter Hemoglobin level"},
                {"name": "Platelets", "key": "Platelets", "type": "number", "placeholder": "Enter Platelets count"},
                {"name": "White Blood Cells", "key": "WhiteBloodCells", "type": "number", "placeholder": "Enter White Blood Cells count"},
                {"name": "Red Blood Cells", "key": "RedBloodCells", "type": "number", "placeholder": "Enter Red Blood Cells count"},
                {"name": "Hematocrit", "key": "Hematocrit", "type": "number", "placeholder": "Enter Hematocrit level"},
                {"name": "Mean Corpuscular Volume", "key": "MeanCorpuscularVolume", "type": "number", "placeholder": "Enter Mean Corpuscular Volume"},
                {"name": "Mean Corpuscular Hemoglobin", "key": "MeanCorpuscularHemoglobin", "type": "number", "placeholder": "Enter Mean Corpuscular Hemoglobin"},
                {"name": "Mean Corpuscular Hemoglobin Concentration", "key": "MeanCorpuscularHemoglobinConcentration", "type": "number", "placeholder": "Enter Mean Corpuscular Hemoglobin Concentration"},
                {"name": "Insulin", "key": "Insulin", "type": "number", "placeholder": "Enter Insulin level"},
                {"name": "BMI", "key": "BMI", "type": "number", "placeholder": "Enter BMI"},
                {"name": "Systolic Blood Pressure", "key": "SystolicBP", "type": "number", "placeholder": "Enter Systolic Blood Pressure"},
                {"name": "Diastolic Blood Pressure", "key": "DiastolicBP", "type": "number", "placeholder": "Enter Diastolic Blood Pressure"},
                {"name": "Triglycerides", "key": "Triglycerides", "type": "number", "placeholder": "Enter Triglycerides level"},
                {"name": "HbA1c", "key": "HbA1c", "type": "number", "placeholder": "Enter HbA1c level"},
                {"name": "LDL Cholesterol", "key": "LDLCholesterol", "type": "number", "placeholder": "Enter LDL Cholesterol level"},
                {"name": "HDL Cholesterol", "key": "HDLCholesterol", "type": "number", "placeholder": "Enter HDL Cholesterol level"},
                {"name": "ALT", "key": "ALT", "type": "number", "placeholder": "Enter ALT level"},
                {"name": "AST", "key": "AST", "type": "number", "placeholder": "Enter AST level"},
                {"name": "Heart Rate", "key": "HeartRate", "type": "number", "placeholder": "Enter Heart Rate"},
                {"name": "Creatinine", "key": "Creatinine", "type": "number", "placeholder": "Enter Creatinine level"},
                {"name": "Troponin", "key": "Troponin", "type": "number", "placeholder": "Enter Troponin level"},
                {"name": "C-reactive Protein", "key": "CReactiveProtein", "type": "number", "placeholder": "Enter C-reactive Protein level"}
            ]
        }

        # Store input widgets
        self.input_widgets = {}
        
        if test_type in ["QUANTUM KIDNEY ANALYSIS", "CARDIAC HOLOGRAM", "LIVER SCAN", "ANEMIA ANALYSIS", "DIABETES PREDICTION"]:
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
            if test_type in ["QUANTUM KIDNEY ANALYSIS", "CARDIAC HOLOGRAM", "LIVER SCAN", "ANEMIA ANALYSIS", "DIABETES PREDICTION"]:
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
        
        # Add Load Test Data button for diabetes prediction
        if test_type == "DIABETES PREDICTION":
            load_test_button = ctk.CTkButton(
                button_frame,
                text="ط·ع¾ط·آ­ط¸â€¦ط¸ظ¹ط¸â€‍ ط·آ¨ط¸ظ¹ط·آ§ط¸â€ ط·آ§ط·ع¾ ط·آ§ط¸â€‍ط·آ§ط·آ®ط·ع¾ط·آ¨ط·آ§ط·آ±",
                command=lambda: load_test_data(),
                width=200,
                height=50,
                corner_radius=10,
                font=("Garamond", 16, "bold"),
                fg_color="#4caf50",
                hover_color="#2e7d32",
                border_width=1,
                border_color="#1b5e20",
                text_color="#ffffff"
            )
            load_test_button.pack(side="left", padx=15)

            def load_test_data():
                test_data = {
                    'Glucose': 0.121786313,
                    'Cholesterol': 0.023058437,
                    'Hemoglobin': 0.94489324,
                    'Platelets': 0.905372145,
                    'WhiteBloodCells': 0.507710985,
                    'RedBloodCells': 0.40303319,
                    'Hematocrit': 0.164216445,
                    'MeanCorpuscularVolume': 0.307553206,
                    'MeanCorpuscularHemoglobin': 0.207938382,
                    'MeanCorpuscularHemoglobinConcentration': 0.505561858,
                    'Insulin': 0.571161502,
                    'BMI': 0.839270508,
                    'SystolicBP': 0.580902575,
                    'DiastolicBP': 0.556037486,
                    'Triglycerides': 0.47774212,
                    'HbA1c': 0.856809908,
                    'LDLCholesterol': 0.652465332,
                    'HDLCholesterol': 0.106960917,
                    'ALT': 0.94254879,
                    'AST': 0.344260902,
                    'HeartRate': 0.66636811,
                    'Creatinine': 0.659059785,
                    'Troponin': 0.816982046,
                    'CReactiveProtein': 0.401165962
                }
                
                # Update input widgets with test data
                for key, value in test_data.items():
                    try:
                        if key in self.input_widgets:
                            widget = self.input_widgets[key]
                            if isinstance(widget, ctk.CTkComboBox):
                                widget.set(value)
                            elif isinstance(widget, ctk.CTkEntry):
                                widget.delete(0, 'end')
                                widget.insert(0, value)
                            print(f"Filled {key} with {value}")
                        else:
                            print(f"Warning: Key {key} not found in input widgets")
                    except Exception as e:
                        print(f"Error setting {key}: {str(e)}")
                messagebox.showinfo("طھظ… ط§ظ„طھط­ظ…ظٹظ„", "طھظ… طھط­ظ…ظٹظ„ ط¨ظٹط§ظ†ط§طھ ط§ظ„ط§ط®طھط¨ط§ط± ط¨ظ†ط¬ط§ط­")

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
            text_color="#ffffff"
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
        results = ctk.CTkToplevel(self.root)
        results.title("Analysis Results")
        results.geometry("1200x800")
        results.configure(fg_color="#e8f5e9")
        
        # Create main container
        container = ctk.CTkFrame(
            results,
            fg_color="#f1f8e9",
            corner_radius=0
        )
        container.pack(expand=True, fill="both", padx=20, pady=20)
        
        if test_type == "LIVER SCAN" and hasattr(self, 'prediction_results'):
            # Create result display frame
            result_frame = ctk.CTkFrame(
                container,
                fg_color="#f5f9f0",
                corner_radius=30,
                border_width=2,
                border_color="#558b2f"
            )
            result_frame.pack(expand=True, fill="both", padx=20, pady=20)
            
            # Display prediction result
            is_at_risk = self.prediction_results["prediction"] == 1
            risk_probability = self.prediction_results["probability"] * 100
            
            # Result header
            result_header = ctk.CTkLabel(
                result_frame,
                text="Liver Disease Risk Assessment",
                font=("Garamond", 36, "bold"),
                text_color="#2e7d32"
            )
            result_header.pack(pady=(30, 20))
            
            # Risk status
            risk_level = ""
            risk_color = ""
            if risk_probability < 2:
                risk_level = "✅ منخفض جداً"
                risk_color = "#2e7d32"  # dark green
            elif risk_probability < 5:
                risk_level = "🟡 منخفض"
                risk_color = "#689f38"  # light green
            elif risk_probability < 15:
                risk_level = "🟠 متوسط"
                risk_color = "#f9a825"  # orange
            elif risk_probability < 30:
                risk_level = "⚠️ متوسط-عالي"
                risk_color = "#ff5722"  # deep orange
            else:
                risk_level = "🔴 عالي جداً"
                risk_color = "#d32f2f"  # red

            risk_label = ctk.CTkLabel(
                result_frame,
                text=f"مستوى الخطورة: {risk_level}",
                font=("Garamond", 48, "bold"),
                text_color=risk_color
            )
            risk_label.pack(pady=(20, 10))
            
            # Risk probability with interpretation
            prob_text = f"نسبة احتمالية الخطر: {risk_probability:.1f}%\n"
            if risk_probability < 2:
                prob_text += "(خطر منخفض جداً - متابعة روتينية)"
            elif risk_probability < 5:
                prob_text += "(خطر منخفض - مراقبة دورية)"
            elif risk_probability < 15:
                prob_text += "(خطر متوسط - يُنصح بمراجعة الطبيب)"
            elif risk_probability < 30:
                prob_text += "(خطر متوسط-عالي - يجب مراجعة الطبيب)"
            else:
                prob_text += "(خطر عالي جداً - مراجعة الطبيب فوراً)"

            prob_label = ctk.CTkLabel(
                result_frame,
                text=prob_text,
                font=("Garamond", 24),
                text_color="#1b5e20"
            )
            prob_label.pack(pady=(0, 30))
            
            # Create scrollable frame for detailed results
            details_frame = ctk.CTkScrollableFrame(
                result_frame,
                fg_color="#ffffff",
                corner_radius=20,
                border_width=1,
                border_color="#558b2f",
                height=300
            )
            details_frame.pack(padx=40, pady=(0, 20), fill="x")
            
            # Display input parameters with proper labels
            param_names = {
                'age': 'Age',
                'gender': 'Gender',
                'total_bilirubin': 'Total Bilirubin',
                'direct_bilirubin': 'Direct Bilirubin',
                'alkphos': 'Alkaline Phosphotase',
                'sgpt': 'SGPT (ALT)',
                'sgot': 'SGOT (AST)',
                'total_proteins': 'Total Proteins',
                'albumin': 'Albumin',
                'ag_ratio': 'Albumin/Globulin Ratio'
            }
            
            # Group parameters by category
            parameter_groups = {
                'Demographics': ['age', 'gender'],
                'Liver Function Tests': [
                    'total_bilirubin', 'direct_bilirubin', 'alkphos',
                    'sgpt', 'sgot', 'total_proteins', 'albumin', 'ag_ratio'
                ]
            }
            
            # Display parameters by group
            for group_name, params in parameter_groups.items():
                # Group header
                group_frame = ctk.CTkFrame(details_frame, fg_color="#f1f8e9")
                group_frame.pack(pady=10, padx=20, fill="x")
                
                group_label = ctk.CTkLabel(
                    group_frame,
                    text=group_name,
                    font=("Garamond", 16, "bold"),
                    text_color="#1b5e20"
                )
                group_label.pack(pady=5, padx=15, anchor="w")
                
                # Parameters in group
                for key in params:
                    if key in self.prediction_results["input_data"]:
                        value = self.prediction_results["input_data"][key]
                        
                        # Format boolean/categorical values
                        if key == 'gender':
                            value = "Male" if value == 1 else "Female"
                        
                        param_frame = ctk.CTkFrame(group_frame, fg_color="transparent")
                        param_frame.pack(pady=2, padx=20, fill="x")
                        
                        param_label = ctk.CTkLabel(
                            param_frame,
                            text=param_names[key],
                            font=("Garamond", 14),
                            text_color="#2e7d32"
                        )
                        param_label.pack(side="left", padx=15)
                        
                        value_label = ctk.CTkLabel(
                            param_frame,
                            text=str(value),
                            font=("Garamond", 14),
                            text_color="#1b5e20"
                        )
                        value_label.pack(side="right", padx=15)
            
            # Risk Factors section
            if hasattr(self.prediction_results, "risk_factors"):
                risk_factors_label = ctk.CTkLabel(
                    result_frame,
                    text="Risk Factors Identified",
                    font=("Garamond", 24, "bold"),
                    text_color="#2e7d32"
                )
                risk_factors_label.pack(pady=(20, 10))
                
                risk_factors_text = "\n".join([f"• {factor}" for factor in self.prediction_results["risk_factors"]])
                if not risk_factors_text:
                    risk_factors_text = "No significant risk factors identified"
                
                risk_factors_details = ctk.CTkLabel(
                    result_frame,
                    text=risk_factors_text,
                    font=("Garamond", 14),
                    text_color="#1b5e20",
                    justify="left"
                )
                risk_factors_details.pack(pady=(0, 20))
            
            # Recommendations section
            recommendations = ctk.CTkLabel(
                result_frame,
                text="Recommendations",
                font=("Garamond", 24, "bold"),
                text_color="#2e7d32"
            )
            recommendations.pack(pady=(20, 10))
            
            if is_at_risk:
                rec_text = (
                    "• Schedule an appointment with a hepatologist\n"
                    "• Follow a liver-friendly diet\n"
                    "• Avoid alcohol consumption\n"
                    "• Regular exercise as tolerated\n"
                    "• Take prescribed medications as directed\n"
                    "• Monitor liver function tests regularly\n"
                    "• Avoid hepatotoxic medications\n"
                    "• Stay well hydrated"
                )
            else:
                rec_text = (
                    "• Continue regular check-ups\n"
                    "• Maintain a healthy diet\n"
                    "• Limit alcohol consumption\n"
                    "• Stay physically active\n"
                    "• Stay well hydrated\n"
                    "• Avoid excessive use of medications that may affect the liver"
                )
            
            rec_details = ctk.CTkLabel(
                result_frame,
                text=rec_text,
                font=("Garamond", 14),
                text_color="#1b5e20",
                justify="left"
            )
            rec_details.pack(pady=(0, 20))
            
        elif test_type == "QUANTUM KIDNEY ANALYSIS" and hasattr(self, 'prediction_results'):
            # Create result display frame
            result_frame = ctk.CTkFrame(
                container,
                fg_color="#f5f9f0",
                corner_radius=30,
                border_width=2,
                border_color="#558b2f"
            )
            result_frame.pack(expand=True, fill="both", padx=20, pady=20)
            
            # Display prediction result
            is_at_risk = self.prediction_results["prediction"] == 1
            risk_probability = self.prediction_results["probability"] * 100
            
            # Result header
            result_header = ctk.CTkLabel(
                result_frame,
                text="Kidney Disease Risk Assessment",
                font=("Garamond", 36, "bold"),
                text_color="#2e7d32"
            )
            result_header.pack(pady=(30, 20))
            
            # Risk status
            risk_level = ""
            risk_color = ""
            if risk_probability < 2:
                risk_level = "✅ منخفض جداً"
                risk_color = "#2e7d32"  # dark green
            elif risk_probability < 5:
                risk_level = "🟡 منخفض"
                risk_color = "#689f38"  # light green
            elif risk_probability < 15:
                risk_level = "🟠 متوسط"
                risk_color = "#f9a825"  # orange
            elif risk_probability < 30:
                risk_level = "⚠️ متوسط-عالي"
                risk_color = "#ff5722"  # deep orange
            else:
                risk_level = "🔴 عالي جداً"
                risk_color = "#d32f2f"  # red

            risk_label = ctk.CTkLabel(
                result_frame,
                text=f"مستوى الخطورة: {risk_level}",
                font=("Garamond", 48, "bold"),
                text_color=risk_color
            )
            risk_label.pack(pady=(20, 10))
            
            # Risk probability with interpretation
            prob_text = f"نسبة احتمالية الخطر: {risk_probability:.1f}%\n"
            if risk_probability < 2:
                prob_text += "(خطر منخفض جداً - متابعة روتينية)"
            elif risk_probability < 5:
                prob_text += "(خطر منخفض - مراقبة دورية)"
            elif risk_probability < 15:
                prob_text += "(خطر متوسط - يُنصح بمراجعة الطبيب)"
            elif risk_probability < 30:
                prob_text += "(خطر متوسط-عالي - يجب مراجعة الطبيب)"
            else:
                prob_text += "(خطر عالي جداً - مراجعة الطبيب فوراً)"

            prob_label = ctk.CTkLabel(
                result_frame,
                text=prob_text,
                font=("Garamond", 24),
                text_color="#1b5e20"
            )
            prob_label.pack(pady=(0, 30))
            
            # Create scrollable frame for detailed results
            details_frame = ctk.CTkScrollableFrame(
                result_frame,
                fg_color="#ffffff",
                corner_radius=20,
                border_width=1,
                border_color="#558b2f",
                height=300
            )
            details_frame.pack(padx=40, pady=(0, 20), fill="x")
            
            # Display input parameters with proper labels
            param_names = {
                'Age': 'Age',
                'Gender': 'Gender',
                'BMI': 'BMI',
                'Smoking': 'Smoking Status',
                'AlcoholConsumption': 'Alcohol Consumption',
                'FamilyHistoryKidneyDisease': 'Family History of Kidney Disease',
                'FamilyHistoryHypertension': 'Family History of Hypertension',
                'FamilyHistoryDiabetes': 'Family History of Diabetes',
                'PreviousAcuteKidneyInjury': 'Previous Acute Kidney Injury',
                'SystolicBP': 'Systolic Blood Pressure',
                'DiastolicBP': 'Diastolic Blood Pressure',
                'FastingBloodSugar': 'Fasting Blood Sugar',
                'HbA1c': 'HbA1c Level',
                'SerumCreatinine': 'Serum Creatinine',
                'BUNLevels': 'BUN Levels',
                'GFR': 'GFR',
                'HemoglobinLevels': 'Hemoglobin Levels',
                'SerumElectrolytesSodium': 'Serum Sodium',
                'SerumElectrolytesPotassium': 'Serum Potassium',
                'SerumElectrolytesCalcium': 'Serum Calcium',
                'SerumElectrolytesPhosphorus': 'Serum Phosphorus',
                'ProteinInUrine': 'Protein in Urine',
                'ACR': 'ACR',
                'Diuretics': 'Diuretics Usage',
                'Edema': 'Edema',
                'FatigueLevels': 'Fatigue Level',
                'NauseaVomiting': 'Nausea/Vomiting',
                'MuscleCramps': 'Muscle Cramps',
                'Itching': 'Itching'
            }
            
            # Group parameters by category
            parameter_groups = {
                'Demographics': ['Age', 'Gender', 'BMI'],
                'Medical History': [
                    'FamilyHistoryKidneyDisease', 'FamilyHistoryHypertension',
                    'FamilyHistoryDiabetes', 'PreviousAcuteKidneyInjury'
                ],
                'Lifestyle': ['Smoking', 'AlcoholConsumption'],
                'Vital Signs': ['SystolicBP', 'DiastolicBP'],
                'Blood Tests': [
                    'FastingBloodSugar', 'HbA1c', 'SerumCreatinine',
                    'BUNLevels', 'GFR', 'HemoglobinLevels'
                ],
                'Electrolytes': [
                    'SerumElectrolytesSodium', 'SerumElectrolytesPotassium',
                    'SerumElectrolytesCalcium', 'SerumElectrolytesPhosphorus'
                ],
                'Urine Tests': ['ProteinInUrine', 'ACR'],
                'Medications': ['Diuretics'],
                'Symptoms': ['Edema', 'FatigueLevels', 'NauseaVomiting', 'MuscleCramps', 'Itching']
            }
            
            # Display parameters by group
            for group_name, params in parameter_groups.items():
                # Group header
                group_frame = ctk.CTkFrame(details_frame, fg_color="#f1f8e9")
                group_frame.pack(pady=10, padx=20, fill="x")
                
                group_label = ctk.CTkLabel(
                    group_frame,
                    text=group_name,
                    font=("Garamond", 16, "bold"),
                    text_color="#1b5e20"
                )
                group_label.pack(pady=5, padx=15, anchor="w")
                
                # Parameters in group
                for key in params:
                    if key in self.prediction_results["input_data"]:
                        value = self.prediction_results["input_data"][key]
                        
                        # Format boolean/categorical values
                        if key == 'Gender':
                            value = "Male" if value == 1 else "Female"
                        elif key in ['Smoking', 'FamilyHistoryKidneyDisease', 'FamilyHistoryHypertension',
                                   'FamilyHistoryDiabetes', 'PreviousAcuteKidneyInjury', 'Diuretics',
                                   'Edema', 'NauseaVomiting', 'MuscleCramps', 'Itching']:
                            value = "Yes" if value == 1 else "No"
                        
                        param_frame = ctk.CTkFrame(group_frame, fg_color="transparent")
                        param_frame.pack(pady=2, padx=20, fill="x")
                        
                        param_label = ctk.CTkLabel(
                            param_frame,
                            text=param_names[key],
                            font=("Garamond", 14),
                            text_color="#2e7d32"
                        )
                        param_label.pack(side="left", padx=15)
                        
                        value_label = ctk.CTkLabel(
                            param_frame,
                            text=str(value),
                            font=("Garamond", 14),
                            text_color="#1b5e20"
                        )
                        value_label.pack(side="right", padx=15)
            
            # Risk Factors section
            if hasattr(self.prediction_results, "risk_factors"):
                risk_factors_label = ctk.CTkLabel(
                    result_frame,
                    text="Risk Factors Identified",
                    font=("Garamond", 24, "bold"),
                    text_color="#2e7d32"
                )
                risk_factors_label.pack(pady=(20, 10))
                
                risk_factors_text = "\n".join([f"• {factor}" for factor in self.prediction_results["risk_factors"]])
                if not risk_factors_text:
                    risk_factors_text = "No significant risk factors identified"
                
                risk_factors_details = ctk.CTkLabel(
                    result_frame,
                    text=risk_factors_text,
                    font=("Garamond", 14),
                    text_color="#1b5e20",
                    justify="left"
                )
                risk_factors_details.pack(pady=(0, 20))
            
            # Recommendations section
            recommendations = ctk.CTkLabel(
                result_frame,
                text="Recommendations",
                font=("Garamond", 24, "bold"),
                text_color="#2e7d32"
            )
            recommendations.pack(pady=(20, 10))
            
            if is_at_risk:
                rec_text = (
                    "• Schedule an appointment with a nephrologist\n"
                    "• Monitor blood pressure and blood sugar regularly\n"
                    "• Follow a kidney-friendly diet\n"
                    "• Stay well hydrated\n"
                    "• Take prescribed medications as directed\n"
                    "• Regular exercise as tolerated\n"
                    "• Quit smoking if applicable\n"
                    "• Limit alcohol consumption"
                )
            else:
                rec_text = (
                    "• Continue regular check-ups\n"
                    "• Maintain a healthy diet\n"
                    "• Stay physically active\n"
                    "• Stay well hydrated\n"
                    "• Monitor blood pressure periodically\n"
                    "• Avoid excessive use of NSAIDs"
                )
            
            rec_details = ctk.CTkLabel(
                result_frame,
                text=rec_text,
                font=("Garamond", 14),
                text_color="#1b5e20",
                justify="left"
            )
            rec_details.pack(pady=(0, 20))
            
        elif test_type == "CARDIAC HOLOGRAM" and hasattr(self, 'prediction_results'):
            # Create result display frame
            result_frame = ctk.CTkFrame(
                container,
                fg_color="#f5f9f0",
                corner_radius=30,
                border_width=2,
                border_color="#558b2f"
            )
            result_frame.pack(expand=True, fill="both", padx=20, pady=20)
            
            # Display prediction result
            is_at_risk = self.prediction_results["prediction"] == 1
            risk_probability = self.prediction_results["probability"] * 100
            
            # Result header
            result_header = ctk.CTkLabel(
                result_frame,
                text="Heart Disease Risk Assessment",
                font=("Garamond", 36, "bold"),
                text_color="#2e7d32"
            )
            result_header.pack(pady=(30, 20))
            
            # Risk status
            risk_level = ""
            risk_color = ""
            if risk_probability < 2:
                risk_level = "✅ منخفض جداً"
                risk_color = "#2e7d32"  # dark green
            elif risk_probability < 5:
                risk_level = "🟡 منخفض"
                risk_color = "#689f38"  # light green
            elif risk_probability < 15:
                risk_level = "🟠 متوسط"
                risk_color = "#f9a825"  # orange
            elif risk_probability < 30:
                risk_level = "⚠️ متوسط-عالي"
                risk_color = "#ff5722"  # deep orange
            else:
                risk_level = "🔴 عالي جداً"
                risk_color = "#d32f2f"  # red

            risk_label = ctk.CTkLabel(
                result_frame,
                text=f"مستوى الخطورة: {risk_level}",
                font=("Garamond", 48, "bold"),
                text_color=risk_color
            )
            risk_label.pack(pady=(20, 10))
            
            # Risk probability with interpretation
            prob_text = f"نسبة احتمالية الخطر: {risk_probability:.1f}%\n"
            if risk_probability < 2:
                prob_text += "(خطر منخفض جداً - متابعة روتينية)"
            elif risk_probability < 5:
                prob_text += "(خطر منخفض - مراقبة دورية)"
            elif risk_probability < 15:
                prob_text += "(خطر متوسط - يُنصح بمراجعة الطبيب)"
            elif risk_probability < 30:
                prob_text += "(خطر متوسط-عالي - يجب مراجعة الطبيب)"
            else:
                prob_text += "(خطر عالي جداً - مراجعة الطبيب فوراً)"

            prob_label = ctk.CTkLabel(
                result_frame,
                text=prob_text,
                font=("Garamond", 24),
                text_color="#1b5e20"
            )
            prob_label.pack(pady=(0, 30))
            
            # Create scrollable frame for detailed results
            details_frame = ctk.CTkScrollableFrame(
                result_frame,
                fg_color="#ffffff",
                corner_radius=20,
                border_width=1,
                border_color="#558b2f",
                height=300
            )
            details_frame.pack(padx=40, pady=(0, 20), fill="x")
            
            # Display input parameters
            param_names = {
                'male': 'Gender',
                'age': 'Age',
                'currentSmoker': 'Current Smoker',
                'cigsPerDay': 'Cigarettes Per Day',
                'BPMeds': 'Blood Pressure Medications',
                'prevalentStroke': 'Prevalent Stroke',
                'prevalentHyp': 'Prevalent Hypertension',
                'diabetes': 'Diabetes',
                'totChol': 'Total Cholesterol',
                'sysBP': 'Systolic Blood Pressure',
                'diaBP': 'Diastolic Blood Pressure',
                'BMI': 'BMI',
                'heartRate': 'Heart Rate',
                'glucose': 'Glucose Level'
            }
            
            for key, name in param_names.items():
                value = self.prediction_results["input_data"][key]
                
                # Format boolean values
                if key in ['male', 'currentSmoker', 'BPMeds', 'prevalentStroke', 'prevalentHyp', 'diabetes']:
                    if key == 'male':
                        value = "Male" if value == 1 else "Female"
                    else:
                        value = "Yes" if value == 1 else "No"
                
                param_frame = ctk.CTkFrame(details_frame, fg_color="transparent")
                param_frame.pack(pady=5, padx=20, fill="x")
                
                param_label = ctk.CTkLabel(
                    param_frame,
                    text=name,
                    font=("Garamond", 14, "bold"),
                    text_color="#2e7d32"
                )
                param_label.pack(side="left", padx=15)
                
                value_label = ctk.CTkLabel(
                    param_frame,
                    text=str(value),
                    font=("Garamond", 14),
                    text_color="#1b5e20"
                )
                value_label.pack(side="right", padx=15)
            
            # Recommendations section
            recommendations = ctk.CTkLabel(
                result_frame,
                text="Recommendations",
                font=("Garamond", 24, "bold"),
                text_color="#2e7d32"
            )
            recommendations.pack(pady=(20, 10))
            
            if is_at_risk:
                rec_text = (
                    "• Schedule an appointment with a cardiologist\n"
                    "• Monitor blood pressure regularly\n"
                    "• Maintain a heart-healthy diet\n"
                    "• Exercise regularly\n"
                    "• Quit smoking if applicable"
                )
            else:
                rec_text = (
                    "• Continue maintaining a healthy lifestyle\n"
                    "• Regular check-ups\n"
                    "• Stay active and exercise\n"
                    "• Maintain a balanced diet"
                )
            
            rec_details = ctk.CTkLabel(
                result_frame,
                text=rec_text,
                font=("Garamond", 14),
                text_color="#1b5e20",
                justify="left"
            )
            rec_details.pack(pady=(0, 20))
            
        elif test_type == "DIABETES PREDICTION":
            # Load diabetes model
            try:
                diabetes_model_1 = joblib.load("diabetes_model_1.pkl")
                print("Diabetes model loaded successfully")
            except Exception as e:
                print(f"Error loading diabetes model: {str(e)}")
                messagebox.showerror("Error", f"Failed to load diabetes model: {str(e)}")
                return
            
            # Process data for diabetes prediction
            try:
                print("Processing diabetes data")
                diabetes_data = {
                    'Glucose': data['Glucose'],
                    'Cholesterol': data['Cholesterol'],
                    'Hemoglobin': data['Hemoglobin'],
                    'Platelets': data['Platelets'],
                    'WhiteBloodCells': data['WhiteBloodCells'],
                    'RedBloodCells': data['RedBloodCells'],
                    'Hematocrit': data['Hematocrit'],
                    'MeanCorpuscularVolume': data['MeanCorpuscularVolume'],
                    'MeanCorpuscularHemoglobin': data['MeanCorpuscularHemoglobin'],
                    'MeanCorpuscularHemoglobinConcentration': data['MeanCorpuscularHemoglobinConcentration'],
                    'Insulin': data['Insulin'],
                    'BMI': data['BMI'],
                    'SystolicBP': data['SystolicBP'],
                    'DiastolicBP': data['DiastolicBP'],
                    'Triglycerides': data['Triglycerides'],
                    'HbA1c': data['HbA1c'],
                    'LDLCholesterol': data['LDLCholesterol'],
                    'HDLCholesterol': data['HDLCholesterol'],
                    'ALT': data['ALT'],
                    'AST': data['AST'],
                    'HeartRate': data['HeartRate'],
                    'Creatinine': data['Creatinine'],
                    'Troponin': data['Troponin'],
                    'CReactiveProtein': data['CReactiveProtein']
                }
                
                # Convert data to DataFrame
                diabetes_df = pd.DataFrame([diabetes_data])
                print("Diabetes DataFrame created")
                
                # Print model information
                print(f"Model type: {type(diabetes_model_1)}")
                print(f"Model shape: {diabetes_model_1.shape}")
                print(f"Data shape: {diabetes_df.shape}")
                print(f"Data columns: {diabetes_df.columns}")
                
                # Make prediction
                prediction = diabetes_model_1.predict(diabetes_df)
                print(f"Prediction: {prediction}")
                
                # Calculate probability
                probability = diabetes_model_1.predict_proba(diabetes_df)[:, 1][0]
                print(f"Probability: {probability}")
                
                # Determine risk factors
                risk_factors = []
                if data['Glucose'] > 100:
                    risk_factors.append("High glucose level")
                if data['Cholesterol'] > 200:
                    risk_factors.append("High cholesterol level")
                if data['Hemoglobin'] < 12:
                    risk_factors.append("Low hemoglobin level")
                if data['Platelets'] < 150000:
                    risk_factors.append("Low platelet count")
                if data['WhiteBloodCells'] > 10000:
                    risk_factors.append("High white blood cell count")
                if data['RedBloodCells'] < 4.2 or data['RedBloodCells'] > 5.8:
                    risk_factors.append("Abnormal red blood cell count")
                if data['Hematocrit'] < 36 or data['Hematocrit'] > 54:
                    risk_factors.append("Abnormal hematocrit level")
                if data['MeanCorpuscularVolume'] < 80 or data['MeanCorpuscularVolume'] > 100:
                    risk_factors.append("Abnormal mean corpuscular volume")
                if data['MeanCorpuscularHemoglobin'] < 27 or data['MeanCorpuscularHemoglobin'] > 33:
                    risk_factors.append("Abnormal mean corpuscular hemoglobin")
                
                # Store results in class attribute
                self.prediction_results = {
                    "prediction": prediction[0],
                    "probability": probability,
                    "input_data": diabetes_data,
                    "risk_factors": risk_factors
                }
                
                return True
            except Exception as e:
                print(f"Error processing diabetes data: {str(e)}")
                messagebox.showerror("Error", f"Error processing diabetes data: {str(e)}")
                return False
        elif test_type == "ANEMIA ANALYSIS" and hasattr(self, 'prediction_results'):
            # Create result display frame
            result_frame = ctk.CTkFrame(
                container,
                fg_color="#f5f9f0",
                corner_radius=30,
                border_width=2,
                border_color="#558b2f"
            )
            result_frame.pack(expand=True, fill="both", padx=20, pady=20)
            
            # Display prediction result
            is_at_risk = self.prediction_results["prediction"] == 1
            risk_probability = self.prediction_results["probability"] * 100
            
            # Result header
            result_header = ctk.CTkLabel(
                result_frame,
                text="Anemia Risk Assessment",
                font=("Garamond", 36, "bold"),
                text_color="#2e7d32"
            )
            result_header.pack(pady=(30, 20))
            
            # Risk status
            risk_level = ""
            risk_color = ""
            if risk_probability < 2:
                risk_level = "✅ منخفض جداً"
                risk_color = "#2e7d32"  # dark green
            elif risk_probability < 5:
                risk_level = "🟡 منخفض"
                risk_color = "#689f38"  # light green
            elif risk_probability < 15:
                risk_level = "🟠 متوسط"
                risk_color = "#f9a825"  # orange
            elif risk_probability < 30:
                risk_level = "⚠️ متوسط-عالي"
                risk_color = "#ff5722"  # deep orange
            else:
                risk_level = "🔴 عالي جداً"
                risk_color = "#d32f2f"  # red

            risk_label = ctk.CTkLabel(
                result_frame,
                text=f"مستوى الخطورة: {risk_level}",
                font=("Garamond", 48, "bold"),
                text_color=risk_color
            )
            risk_label.pack(pady=(20, 10))
            
            # Risk probability with interpretation
            prob_text = f"نسبة احتمالية الخطر: {risk_probability:.1f}%\n"
            if risk_probability < 2:
                prob_text += "(خطر منخفض جداً - متابعة روتينية)"
            elif risk_probability < 5:
                prob_text += "(خطر منخفض - مراقبة دورية)"
            elif risk_probability < 15:
                prob_text += "(خطر متوسط - يُنصح بمراجعة الطبيب)"
            elif risk_probability < 30:
                prob_text += "(خطر متوسط-عالي - يجب مراجعة الطبيب)"
            else:
                prob_text += "(خطر عالي جداً - مراجعة الطبيب فوراً)"

            prob_label = ctk.CTkLabel(
                result_frame,
                text=prob_text,
                font=("Garamond", 24),
                text_color="#1b5e20"
            )
            prob_label.pack(pady=(0, 30))
            
            # Create scrollable frame for detailed results
            details_frame = ctk.CTkScrollableFrame(
                result_frame,
                fg_color="#ffffff",
                corner_radius=20,
                border_width=1,
                border_color="#558b2f",
                height=300
            )
            details_frame.pack(padx=40, pady=(0, 20), fill="x")
            
            # Display input parameters with proper labels
            param_names = {
                'WBC': 'White Blood Cell count',
                'LYMp': 'Lymphocyte percentage',
                'NEUTp': 'Neutrophil percentage',
                'LYMn': 'Lymphocyte absolute count',
                'NEUTn': 'Neutrophil absolute count',
                'RBC': 'Red Blood Cell count',
                'HGB': 'Hemoglobin level',
                'HCT': 'Hematocrit percentage',
                'MCV': 'Mean Corpuscular Volume',
                'MCH': 'Mean Corpuscular Hemoglobin',
                'MCHC': 'Mean Corpuscular Hemoglobin Concentration',
                'PLT': 'Platelet count',
                'PDW': 'Platelet Distribution Width',
                'PCT': 'Plateletcrit'
            }
            
            # Group parameters by category
            parameter_groups = {
                'Blood Counts': ['WBC', 'LYMp', 'NEUTp', 'LYMn', 'NEUTn', 'RBC', 'HGB', 'HCT', 'MCV', 'MCH', 'MCHC', 'PLT', 'PDW', 'PCT']
            }
            
            # Display parameters by group
            for group_name, params in parameter_groups.items():
                # Group header
                group_frame = ctk.CTkFrame(details_frame, fg_color="#f1f8e9")
                group_frame.pack(pady=10, padx=20, fill="x")
                
                group_label = ctk.CTkLabel(
                    group_frame,
                    text=group_name,
                    font=("Garamond", 16, "bold"),
                    text_color="#1b5e20"
                )
                group_label.pack(pady=5, padx=15, anchor="w")
                
                # Parameters in group
                for key in params:
                    if key in self.prediction_results["input_data"]:
                        value = self.prediction_results["input_data"][key]
                        
                        param_frame = ctk.CTkFrame(group_frame, fg_color="transparent")
                        param_frame.pack(pady=2, padx=20, fill="x")
                        
                        param_label = ctk.CTkLabel(
                            param_frame,
                            text=param_names[key],
                            font=("Garamond", 14),
                            text_color="#2e7d32"
                        )
                        param_label.pack(side="left", padx=15)
                        
                        value_label = ctk.CTkLabel(
                            param_frame,
                            text=str(value),
                            font=("Garamond", 14),
                            text_color="#1b5e20"
                        )
                        value_label.pack(side="right", padx=15)
            
            # Risk Factors section
            if hasattr(self.prediction_results, "risk_factors"):
                risk_factors_label = ctk.CTkLabel(
                    result_frame,
                    text="Risk Factors Identified",
                    font=("Garamond", 24, "bold"),
                    text_color="#2e7d32"
                )
                risk_factors_label.pack(pady=(20, 10))
                
                risk_factors_text = "\n".join([f"• {factor}" for factor in self.prediction_results["risk_factors"]])
                if not risk_factors_text:
                    risk_factors_text = "No significant risk factors identified"
                
                risk_factors_details = ctk.CTkLabel(
                    result_frame,
                    text=risk_factors_text,
                    font=("Garamond", 14),
                    text_color="#1b5e20",
                    justify="left"
                )
                risk_factors_details.pack(pady=(0, 20))
            
            # Recommendations section
            recommendations = ctk.CTkLabel(
                result_frame,
                text="Recommendations",
                font=("Garamond", 24, "bold"),
                text_color="#2e7d32"
            )
            recommendations.pack(pady=(20, 10))
            
            if is_at_risk:
                rec_text = (
                    "• Schedule an appointment with a hematologist\n"
                    "• Follow a balanced diet\n"
                    "• Regular exercise\n"
                    "• Avoid iron deficiency\n"
                    "• Take prescribed medications as directed\n"
                    "• Monitor blood counts regularly\n"
                    "• Stay well hydrated"
                )
            else:
                rec_text = (
                    "• Continue regular check-ups\n"
                    "• Maintain a healthy diet\n"
                    "• Stay physically active\n"
                    "• Stay well hydrated\n"
                    "• Monitor blood counts periodically\n"
                    "• Avoid excessive use of medications that may affect blood counts"
                )
            
            rec_details = ctk.CTkLabel(
                result_frame,
                text=rec_text,
                font=("Garamond", 14),
                text_color="#1b5e20",
                justify="left"
            )
            rec_details.pack(pady=(0, 20))
            
        else:
            # Handle other test types or missing prediction results
            error_label = ctk.CTkLabel(
                container,
                text="No prediction results available",
                font=("Garamond", 24, "bold"),
                text_color="#d32f2f"
            )
            error_label.pack(expand=True)

        # Close button
        close_button = ctk.CTkButton(
            container,
            text="CLOSE",
            command=results.destroy,
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
        close_button.pack(pady=25)

    def run(self):
        self.root.mainloop()

if __name__ == "__main__":
    app = NeonHealthApp()
    app.run()                      
