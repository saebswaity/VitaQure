import customtkinter as ctk
import tkinter as tk
from PIL import Image, ImageTk
import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from xgboost import XGBClassifier
import joblib

class HealthSystem:
    def __init__(self):
        self.root = ctk.CTk()
        self.root.title("VitaGuard AI")
        self.root.geometry("1200x800")
        
        # تعيين المظهر والألوان
        ctk.set_appearance_mode("light")
        ctk.set_default_color_theme("green")
        self.root.configure(bg="#e8f5e9")
        
        # إنشاء الإطار الرئيسي
        self.main_frame = ctk.CTkFrame(
            self.root,
            fg_color="#f1f8e9",
            corner_radius=20,
            border_width=2,
            border_color="#558b2f"
        )
        self.main_frame.pack(expand=True, fill="both", padx=30, pady=30)
        
        # تحميل النماذج
        self.load_models()
        
        # عرض شاشة تسجيل الدخول
        self.show_login()
        
        # بدء حلقة التحديث
        self.animation_frame = 0
        self.neon_intensity = 0
        self.animate()

    def load_models(self):
        """تحميل نماذج التعلم الآلي"""
        try:
            self.heart_model = joblib.load('models/heart_model.pkl')
            self.diabetes_model = joblib.load('models/diabetes_model.pkl')
            self.liver_model = joblib.load('models/liver_model.pkl')
            self.anemia_model = joblib.load('models/anemia_model.pkl')
            self.kidney_model = joblib.load('models/kidney_model.pkl')
        except:
            print("لم يتم العثور على النماذج المدربة")

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

        title = ctk.CTkLabel(
            login_frame,
            text="VitaGuard AI",
            font=("Garamond", 36, "bold"),
            text_color="#2e7d32"
        )
        title.pack(pady=(40, 30))

        # إضافة باقي عناصر تسجيل الدخول...
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

        # إنشاء الأزرار العلوية
        buttons_frame = ctk.CTkFrame(self.main_frame, fg_color="transparent")
        buttons_frame.pack(fill="x", padx=40, pady=20)

        tests = {
            "Heart": "🫀 CARDIAC SCAN",
            "Kidney": "🫘 KIDNEY TEST",
            "Liver": "🫁 LIVER SCAN",
            "Diabetes": "🔬 DIABETES TEST",
            "Anemia": "🩸 ANEMIA TEST"
        }

        for test, full_name in tests.items():
            self.create_test_button(buttons_frame, test, full_name)

        # إضافة صورة الجسم والأزرار عليها
        self.create_body_image()

    def create_test_button(self, parent, test_name, full_name):
        """إنشاء أزرار الفحوصات"""
        button = ctk.CTkButton(
            parent,
            text=full_name,
            command=lambda: self.show_test(test_name),
            width=180,
            height=45,
            corner_radius=10,
            font=("Garamond", 16, "bold")
        )
        button.pack(side="left", padx=25, pady=15)

    def create_body_image(self):
        """إنشاء صورة الجسم والأزرار التفاعلية"""
        canvas = tk.Canvas(
            self.main_frame,
            bg='#f1f8e9',
            highlightthickness=0,
            width=800,
            height=900
        )
        canvas.pack(expand=True, padx=20, pady=20)

        # تحميل وعرض صورة الجسم
        try:
            image = Image.open("x.png")
            image = image.resize((700, 800))
            self.body_image = ImageTk.PhotoImage(image)
            canvas.create_image(400, 350, image=self.body_image, anchor='center')
        except:
            print("لم يتم العثور على صورة الجسم")

    def show_test(self, test_type):
        """عرض نموذج الفحص المحدد"""
        # تنفيذ الفحص المطلوب وعرض النتائج
        pass

    def animate(self):
        """تحديث تأثيرات الإضاءة"""
        self.animation_frame = (self.animation_frame + 1) % 360
        self.neon_intensity = abs(np.sin(np.radians(self.animation_frame)))
        
        # تحديث الألوان والتأثيرات
        self.root.after(50, self.animate)

if __name__ == "__main__":
    app = HealthSystem()
    app.root.mainloop() 