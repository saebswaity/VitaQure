import customtkinter as ctk
import tkinter as tk
from PIL import Image, ImageTk
import pandas as pd
import numpy as np
from xgboost import XGBClassifier
from sklearn.ensemble import RandomForestClassifier, ExtraTreesClassifier
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import StandardScaler, MinMaxScaler
from sklearn.compose import ColumnTransformer
from sklearn.impute import SimpleImputer
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix
import joblib
from tkinter import messagebox

class VitaGuardAI:
    def __init__(self):
        self.root = ctk.CTk()
        self.root.title("VitaGuard AI")
        self.root.geometry("1200x800")
        
        # تكوين النافذة الرئيسية بنفس الألوان
        ctk.set_appearance_mode("light")
        ctk.set_default_color_theme("green")
        self.root.configure(bg="#e8f5e9")
        
        # إنشاء الإطار الرئيسي بنفس التصميم
        self.main_frame = ctk.CTkFrame(
            self.root,
            fg_color="#f1f8e9",
            corner_radius=20,
            border_width=2,
            border_color="#558b2f"
        )
        self.main_frame.pack(expand=True, fill="both", padx=30, pady=30)
        
        # تهيئة نموذج XGBoost كما في الكود الأصلي
        self.model = None
        self.scaler = StandardScaler()
        self.load_and_train_model()
        
        # تهيئة متغيرات الحركة
        self.animation_frame = 0
        self.neon_intensity = 0
        self.animate()
        
        # تعريف مواقع الأزرار بنفس الإحداثيات
        self.button_positions = {
            "Heart": (410, 390),
            "Diabetes": (450, 485),
            "Kidney": (380, 500),
            "Body": (390, 450),
            "Liver": (245, 450)
        }
        
        # تعريف أحجام الأزرار بنفس القيم
        self.button_sizes = {
            "Heart": {"normal": (120, 120), "hover": (200, 200)},
            "Diabetes": {"normal": (80, 80), "hover": (100, 100)},
            "Kidney": {"normal": (120, 45), "hover": (150, 70)},
            "Body": {"normal": (40, 40), "hover": (60, 60)},
            "Liver": {"normal": (60, 60), "hover": (90, 90)}
        }
        
        # تهيئة قاموس لصور الأزرار
        self.button_images = {}
        
        # بدء التطبيق بشاشة تسجيل الدخول
        self.show_login()

    def initialize_models(self):
        """تهيئة نماذج التعلم الآلي"""
        # نموذج القلب
        self.heart_model = XGBClassifier(
            objective='binary:logistic',
            n_estimators=1000,
            max_depth=6,
            learning_rate=0.05
        )
        
        # نموذج السكري
        self.diabetes_model = RandomForestClassifier(
            n_estimators=100,
            max_depth=6,
            random_state=42
        )
        
        # نموذج الكبد
        self.liver_model = RandomForestClassifier(
            n_estimators=100,
            random_state=42
        )
        
        # نموذج الكلى
        self.kidney_model = XGBClassifier(
            objective="binary:logistic",
            eval_metric="logloss",
            seed=42
        )
        
        # نموذج فقر الدم
        self.anemia_model = XGBClassifier(
            use_label_encoder=False,
            eval_metric="logloss"
        )

    def show_login(self):
        """عرض شاشة تسجيل الدخول"""
        for widget in self.main_frame.winfo_children():
            widget.destroy()

        login_frame = ctk.CTkFrame(
            self.main_frame,
            fg_color="#f5f9f0",
            corner_radius=30,
            border_width=2,
            border_color="#558b2f"
        )
        login_frame.pack(expand=True, padx=200, pady=100)

        # عنوان التطبيق
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

        # حقول الإدخال
        username = ctk.CTkEntry(
            login_frame,
            placeholder_text="Username",
            width=300,
            height=50,
            corner_radius=10,
            border_color="#558b2f",
            fg_color="#ffffff",
            text_color="#1b5e20"
        )
        username.pack(pady=10)

        password = ctk.CTkEntry(
            login_frame,
            placeholder_text="Password",
            show="●",
            width=300,
            height=50,
            corner_radius=10,
            border_color="#558b2f",
            fg_color="#ffffff",
            text_color="#1b5e20"
        )
        password.pack(pady=10)

        # زر تسجيل الدخول
        login_button = ctk.CTkButton(
            login_frame,
            text="LOGIN",
            command=self.show_main_menu,
            width=200,
            height=50,
            corner_radius=15,
            font=("Garamond", 20, "bold"),
            fg_color="#66bb6a",
            hover_color="#43a047"
        )
        login_button.pack(pady=(20, 40))

    def show_main_menu(self):
        """عرض القائمة الرئيسية"""
        for widget in self.main_frame.winfo_children():
            widget.destroy()

        # إنشاء الحاوية الرئيسية
        container = ctk.CTkFrame(
            self.main_frame,
            fg_color="#f1f8e9",
            corner_radius=0
        )
        container.pack(expand=True, fill="both")

        # الشريط الجانبي
        sidebar = self.create_sidebar(container)
        
        # محتوى الصفحة الرئيسية
        content = ctk.CTkFrame(
            container,
            fg_color="#f5f9f0",
            corner_radius=0
        )
        content.pack(side="right", fill="both", expand=True)

        # عنوان الصفحة
        title = ctk.CTkLabel(
            content,
            text="VitaGuard AI",
            font=("Garamond", 50, "bold"),
            text_color="#2e7d32"
        )
        title.pack(pady=(50, 30))

        # إنشاء الأزرار العلوية
        self.create_top_buttons(content)
        
        # إنشاء صورة الجسم والأزرار التفاعلية
        self.create_body_image(content)

    def create_sidebar(self, parent):
        """إنشاء الشريط الجانبي"""
        sidebar = ctk.CTkFrame(
            parent,
            fg_color="#00FF7F",
            corner_radius=0,
            width=200
        )
        sidebar.pack(side="left", fill="y")
        sidebar.pack_propagate(False)

        # زر تسجيل الخروج
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
            command=self.show_login,
            font=("Arial Black", 14),
            fg_color="transparent",
            hover_color="#333333",
            text_color="white",
            corner_radius=20,
            height=40,
            width=160
        )
        logout_button.pack(pady=5, padx=5)

        return sidebar

    def create_top_buttons(self, parent):
        """إنشاء الأزرار العلوية"""
        buttons_frame = ctk.CTkFrame(
            parent,
            fg_color="transparent"
        )
        buttons_frame.pack(fill="x", padx=40, pady=20)

        tests = {
            "Heart": "🫀 CARDIAC SCAN",
            "Kidney": "🫘 KIDNEY TEST",
            "Liver": "🫁 LIVER SCAN",
            "Diabetes": "🔬 DIABETES TEST",
            "Anemia": "🩸 ANEMIA TEST"
        }

        for test, full_name in tests.items():
            button = ctk.CTkButton(
                buttons_frame,
                text=full_name,
                command=lambda t=test: self.show_test(t),
                width=180,
                height=45,
                corner_radius=10,
                font=("Garamond", 16, "bold")
            )
            button.pack(side="left", padx=25, pady=15)

    def create_body_image(self, parent):
        """إنشاء صورة الجسم والأزرار التفاعلية"""
        canvas = tk.Canvas(
            parent,
            bg='#f5f9f0',
            highlightthickness=0,
            width=800,
            height=900
        )
        canvas.pack(expand=True, padx=20, pady=20)

        try:
            image = Image.open("x.png")
            image = image.resize((700, 800))
            self.body_image = ImageTk.PhotoImage(image)
            canvas.create_image(400, 350, image=self.body_image, anchor='center')
            
            # إنشاء الأزرار على صورة الجسم
            for test, pos in self.button_positions.items():
                self.create_body_button(canvas, test, pos)
        except Exception as e:
            print(f"Error loading body image: {str(e)}")

    def create_body_button(self, canvas, test, position):
        """إنشاء زر على صورة الجسم"""
        normal_size = self.button_sizes[test]["normal"]
        hover_size = self.button_sizes[test]["hover"]
        
        try:
            # تحميل صورة الزر
            button_image = Image.open(f"buttons/{test.lower()}.png")
            button_image = button_image.resize(normal_size)
            self.button_images[test] = ImageTk.PhotoImage(button_image)
            
            # إنشاء الزر على الكانفاس
            button = canvas.create_image(
                position[0],
                position[1],
                image=self.button_images[test],
                anchor='center',
                tags=test
            )
            
            # ربط الأحداث
            canvas.tag_bind(test, '<Enter>', lambda e, t=test: self.on_button_hover(e, t))
            canvas.tag_bind(test, '<Leave>', lambda e, t=test: self.on_button_leave(e, t))
            canvas.tag_bind(test, '<Button-1>', lambda e, t=test: self.show_test(t))
            
        except Exception as e:
            print(f"Error creating button {test}: {str(e)}")

    def on_button_hover(self, event, test):
        """تأثير تحويم الماوس على الزر"""
        hover_size = self.button_sizes[test]["hover"]
        try:
            button_image = Image.open(f"buttons/{test.lower()}.png")
            button_image = button_image.resize(hover_size)
            self.button_images[test] = ImageTk.PhotoImage(button_image)
            
            # تحديث صورة الزر
            canvas = event.widget
            canvas.itemconfig(test, image=self.button_images[test])
        except Exception as e:
            print(f"Error on hover {test}: {str(e)}")

    def on_button_leave(self, event, test):
        """إزالة تأثير التحويم"""
        normal_size = self.button_sizes[test]["normal"]
        try:
            button_image = Image.open(f"buttons/{test.lower()}.png")
            button_image = button_image.resize(normal_size)
            self.button_images[test] = ImageTk.PhotoImage(button_image)
            
            # تحديث صورة الزر
            canvas = event.widget
            canvas.itemconfig(test, image=self.button_images[test])
        except Exception as e:
            print(f"Error on leave {test}: {str(e)}")

    def show_test(self, test_type):
        """عرض نافذة الفحص"""
        test_window = TestWindow(self.root, test_type, self)
        test_window.grab_set()

    def animate(self):
        """تحديث تأثيرات الإضاءة"""
        self.animation_frame = (self.animation_frame + 1) % 360
        self.neon_intensity = abs(np.sin(np.radians(self.animation_frame)))
        
        # تحديث الألوان
        neon_color = f"#{int(self.neon_intensity * 255):02x}ff7f"
        self.root.after(50, self.animate)

    def load_and_train_model(self):
        """تحميل وتدريب النماذج"""
        try:
            # قراءة البيانات
            data = pd.read_csv('cleaned_normalized_shuffled_data.csv')
            
            # تقسيم البيانات إلى ميزات وهدف
            X = data.drop('Diagnosis', axis=1)
            y = data['Diagnosis']
            
            # تقسيم البيانات إلى تدريب واختبار
            X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
            
            # تطبيع البيانات
            X_train = self.scaler.fit_transform(X_train)
            X_test = self.scaler.transform(X_test)
            
            # تدريب النموذج
            self.model = XGBClassifier(
                learning_rate=0.1,
                n_estimators=100,
                max_depth=5,
                random_state=42
            )
            self.model.fit(X_train, y_train)
            
            # تقييم النموذج
            y_pred = self.model.predict(X_test)
            accuracy = accuracy_score(y_test, y_pred)
            print(f"Model Accuracy: {accuracy:.4f}")
            
        except Exception as e:
            print(f"Error loading model: {str(e)}")
            messagebox.showerror("Error", "Failed to load the prediction model")

class TestWindow(ctk.CTkToplevel):
    def __init__(self, parent, test_type, main_app):
        super().__init__(parent)
        self.title(f"{test_type} Test")
        self.geometry("800x600")
        self.main_app = main_app
        
        self.create_test_form(test_type)

    def create_test_form(self, test_type):
        """إنشاء نموذج الفحص"""
        # تحديد الحقول المطلوبة لكل نوع فحص
        fields = self.get_test_fields(test_type)
        
        # إنشاء الحقول
        self.entries = {}
        for field in fields:
            self.create_field(field)
            
        # زر تنفيذ الفحص
        submit_button = ctk.CTkButton(
            self,
            text="Run Test",
            command=lambda: self.run_test(test_type),
            width=200,
            height=50
        )
        submit_button.pack(pady=20)

    def get_test_fields(self, test_type):
        """تحديد الحقول المطلوبة لكل نوع فحص"""
        fields = {
            "Heart": [
                "Heart Rate",
                "Blood Pressure",
                "Cholesterol",
                "Age",
                "Smoking Status",
                "Exercise Level"
            ],
            "Diabetes": [
                "Blood Sugar",
                "BMI",
                "Age",
                "Family History",
                "Physical Activity",
                "Diet Type"
            ],
            "Liver": [
                "Total Bilirubin",
                "Direct Bilirubin",
                "ALT",
                "AST",
                "Total Proteins",
                "Albumin"
            ],
            "Kidney": [
                "Blood Pressure",
                "Specific Gravity",
                "Albumin",
                "Sugar",
                "Red Blood Cells",
                "Pus Cell"
            ],
            "Anemia": [
                "Hemoglobin",
                "MCH",
                "MCHC",
                "MCV",
                "Gender",
                "Age"
            ]
        }
        return fields.get(test_type, [])

    def create_field(self, field_name):
        """إنشاء حقل إدخال"""
        frame = ctk.CTkFrame(self)
        frame.pack(fill="x", padx=20, pady=5)
        
        label = ctk.CTkLabel(
            frame,
            text=field_name,
            font=("Garamond", 14)
        )
        label.pack(side="left", padx=5)
        
        entry = ctk.CTkEntry(
            frame,
            width=200,
            height=30
        )
        entry.pack(side="right", padx=5)
        
        self.entries[field_name] = entry

    def run_test(self, test_type):
        """تنفيذ الفحص وعرض النتائج"""
        # جمع البيانات من الحقول
        data = {}
        for field, entry in self.entries.items():
            try:
                data[field] = float(entry.get())
            except ValueError:
                data[field] = entry.get()
        
        # تحويل البيانات إلى DataFrame
        df = pd.DataFrame([data])
        
        # اختيار النموذج المناسب
        if test_type == "Heart":
            model = self.main_app.heart_model
        elif test_type == "Diabetes":
            model = self.main_app.diabetes_model
        elif test_type == "Liver":
            model = self.main_app.liver_model
        elif test_type == "Kidney":
            model = self.main_app.kidney_model
        elif test_type == "Anemia":
            model = self.main_app.anemia_model
        
        try:
            # التنبؤ
            prediction = model.predict(df)
            probabilities = model.predict_proba(df)
            
            # عرض النتائج
            self.show_results(test_type, prediction[0], probabilities[0])
        except Exception as e:
            print(f"Error in prediction: {str(e)}")

    def show_results(self, test_type, prediction, probabilities):
        """عرض نتائج الفحص"""
        results_window = ctk.CTkToplevel(self)
        results_window.title(f"{test_type} Test Results")
        results_window.geometry("600x400")
        
        # عنوان النتائج
        title = ctk.CTkLabel(
            results_window,
            text="Test Results",
            font=("Garamond", 24, "bold")
        )
        title.pack(pady=20)
        
        # النتيجة الرئيسية
        result_text = "Positive" if prediction == 1 else "Negative"
        result_label = ctk.CTkLabel(
            results_window,
            text=f"Result: {result_text}",
            font=("Garamond", 18)
        )
        result_label.pack(pady=10)
        
        # نسب الاحتمالات
        prob_negative = probabilities[0] * 100
        prob_positive = probabilities[1] * 100
        
        prob_label = ctk.CTkLabel(
            results_window,
            text=f"Probability:\nNegative: {prob_negative:.1f}%\nPositive: {prob_positive:.1f}%",
            font=("Garamond", 16)
        )
        prob_label.pack(pady=10)

if __name__ == "__main__":
    app = VitaGuardAI()
    app.root.mainloop() 