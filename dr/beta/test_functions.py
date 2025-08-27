import numpy as np
import pandas as pd

def perform_heart_test(model, data):
    """
    تنفيذ فحص القلب
    المدخلات المطلوبة:
    - معدل ضربات القلب
    - ضغط الدم
    - الكوليسترول
    - العمر
    """
    try:
        prediction = model.predict(data)
        probabilities = model.predict_proba(data)
        return prediction[0], probabilities[0]
    except Exception as e:
        print(f"خطأ في فحص القلب: {str(e)}")
        return None, None

def perform_diabetes_test(model, data):
    """
    تنفيذ فحص السكري
    المدخلات المطلوبة:
    - مستوى السكر في الدم
    - مؤشر كتلة الجسم
    - العمر
    - التاريخ العائلي
    """
    try:
        prediction = model.predict(data)
        probabilities = model.predict_proba(data)
        return prediction[0], probabilities[0]
    except Exception as e:
        print(f"خطأ في فحص السكري: {str(e)}")
        return None, None

# ... باقي دوال الفحص 