import tkinter as tk
from tkinter import ttk, messagebox
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split, cross_val_score, StratifiedKFold
from sklearn.preprocessing import StandardScaler
import xgboost as xgb
import matplotlib.pyplot as plt
from matplotlib.backends.backend_tkagg import FigureCanvasTkAgg

class ModelInterface:
    def __init__(self, root):
        self.root = root
        self.root.title("نموذج التحليل المتكامل")
        self.root.geometry("1000x800")
        
        # إنشاء الإطار الرئيسي
        self.main_frame = ttk.Frame(self.root, padding="10")
        self.main_frame.grid(row=0, column=0, sticky=(tk.W, tk.E, tk.N, tk.S))
        
        # أزرار التحكم
        self.create_controls()
        
        # منطقة عرض النتائج
        self.result_text = tk.Text(self.main_frame, height=10, width=60)
        self.result_text.grid(row=1, column=0, columnspan=2, pady=10)
        
        # إعداد منطقة الرسم البياني
        self.fig, self.ax = plt.subplots(figsize=(8, 6))
        self.canvas = FigureCanvasTkAgg(self.fig, master=self.main_frame)
        self.canvas.get_tk_widget().grid(row=2, column=0, columnspan=2)

    def create_controls(self):
        # زر تحميل البيانات
        self.load_button = ttk.Button(self.main_frame, text="تحميل البيانات", command=self.load_data)
        self.load_button.grid(row=0, column=0, padx=5, pady=5)
        
        # زر تدريب النموذج
        self.train_button = ttk.Button(self.main_frame, text="تدريب النموذج", command=self.train_model)
        self.train_button.grid(row=0, column=1, padx=5, pady=5)
        
        # زر تقدير عدم اليقين
        self.uncertainty_button = ttk.Button(self.main_frame, text="حساب عدم اليقين", command=self.calculate_uncertainty)
        self.uncertainty_button.grid(row=0, column=2, padx=5, pady=5)

    def load_data(self):
        # هنا يمكنك إضافة كود تحميل البيانات
        try:
            # مثال على تحميل البيانات
            self.data = pd.read_csv("your_data.csv")
            messagebox.showinfo("نجاح", "تم تحميل البيانات بنجاح")
        except:
            messagebox.showerror("خطأ", "حدث خطأ في تحميل البيانات")

    def train_model(self):
        # تدريب النموذج
        try:
            # هنا يتم إضافة كود تدريب النموذج XGBoost
            self.model = xgb.XGBRegressor()
            # تدريب النموذج...
            messagebox.showinfo("نجاح", "تم تدريب النموذج بنجاح")
        except:
            messagebox.showerror("خطأ", "حدث خطأ في تدريب النموذج")

    def model_uncertainty_estimate(self, model, feature, noise_std=0.07, num_samples=100):
        # تنفيذ تقدير عدم اليقين
        noisy_features = np.array([feature + np.random.normal(0, noise_std, feature.shape) 
                                 for _ in range(num_samples)])
        predictions = np.array([model.predict(f) for f in noisy_features])
        mean_pred = np.mean(predictions, axis=0)
        std_pred = np.std(predictions, axis=0)
        return mean_pred, std_pred

    def calculate_uncertainty(self):
        try:
            # حساب عدم اليقين وعرض النتائج
            mean_pred, std_pred = self.model_uncertainty_estimate(self.model, self.feature_data)
            
            # رسم النتائج
            self.ax.clear()
            self.ax.plot(mean_pred, label='التنبؤات')
            self.ax.fill_between(range(len(mean_pred)), 
                               mean_pred - 2*std_pred, 
                               mean_pred + 2*std_pred, 
                               alpha=0.2, label='نطاق عدم اليقين')
            self.ax.legend()
            self.canvas.draw()
            
            # عرض النتائج في منطقة النص
            self.result_text.delete(1.0, tk.END)
            self.result_text.insert(tk.END, f"متوسط التنبؤات: {np.mean(mean_pred):.4f}\n")
            self.result_text.insert(tk.END, f"متوسط عدم اليقين: {np.mean(std_pred):.4f}")
            
        except:
            messagebox.showerror("خطأ", "حدث خطأ في حساب عدم اليقين")

if __name__ == "__main__":
    root = tk.Tk()
    app = ModelInterface(root)
    root.mainloop() 