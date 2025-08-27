import customtkinter as ctk

class TestInputWindow:
    def __init__(self, parent, test_type):
        self.window = ctk.CTkToplevel(parent)
        self.window.title(f"{test_type} Test")
        self.window.geometry("600x800")
        
        self.create_input_fields(test_type)

    def create_input_fields(self, test_type):
        """إنشاء حقول الإدخال حسب نوع الفحص"""
        if test_type == "Heart":
            fields = [
                "Heart Rate",
                "Blood Pressure",
                "Cholesterol",
                "Age",
                "Smoking Status",
                "Exercise Level"
            ]
        elif test_type == "Diabetes":
            fields = [
                "Blood Sugar",
                "BMI",
                "Age",
                "Family History",
                "Physical Activity",
                "Diet Type"
            ]
        # ... باقي أنواع الفحوصات

        self.entries = {}
        for field in fields:
            self.create_field(field)

    def create_field(self, field_name):
        """إنشاء حقل إدخال"""
        frame = ctk.CTkFrame(self.window)
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