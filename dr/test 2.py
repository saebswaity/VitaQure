import customtkinter as ctk
import tkinter as tk
from tkinter import messagebox
import time
import math
from PIL import Image, ImageTk

class NeonHealthApp:
    def __init__(self):
        self.root = ctk.CTk()
        self.root.title("VitaGuard AI")
        self.root.geometry("1200x800")  # عرض أكبر للأزرار الجانبية
        
        # تغيير الثيم إلى الطبيعة
        ctk.set_appearance_mode("light")
        ctk.set_default_color_theme("green")
        
        # تكوين النافذة الرئيسية
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
        
        # Initialize animation
        self.animation_frame = 0
        self.neon_intensity = 0
        self.animate()
        
        self.show_login()

    def animate(self):
        self.animation_frame += 1
        self.neon_intensity = abs(math.sin(self.animation_frame / 30)) * 0.3 + 0.7
        
        r = int(0)
        g = int(255 * self.neon_intensity)
        b = int(255 * self.neon_intensity)
        neon_color = f"#{r:02x}{g:02x}{b:02x}"
        self.main_frame.configure(border_color=neon_color)
        
        self.root.after(50, self.animate)

    def show_login(self):
        # مسح الواجهة السابقة
        for widget in self.main_frame.winfo_children():
            widget.destroy()
            
        # إنشاء إطار تسجيل الدخول
        login_frame = ctk.CTkFrame(
            self.main_frame,
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
        for widget in self.main_frame.winfo_children():
            widget.destroy()
            
        # Create main container with sidebar and content
        container = ctk.CTkFrame(
            self.main_frame,
            fg_color="#f1f8e9",
            corner_radius=0
        )
        container.pack(expand=True, fill="both")
        
        # Sidebar frame
        sidebar = ctk.CTkFrame(
            container,
            fg_color="#00FF7F",  # لون أخضر نيون
            corner_radius=0,
            width=200
        )
        sidebar.pack(side="left", fill="y")
        sidebar.pack_propagate(False)  # منع تغيير حجم الشريط الجانبي

        # إضافة خط أسود بجانب الشريط
        separator = ctk.CTkFrame(
            container,
            fg_color="black",
            width=2,
            corner_radius=0
        )
        separator.pack(side="left", fill="y")
        
        # إضافة زر تسجيل الخروج في أعلى الشريط الجانبي
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
        
        # Search button with black background
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
        
        # Menu items with enhanced design
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
        top_buttons_frame.pack(fill="x", padx=20, pady=20)

        # Add horizontal buttons
        button_names = ["Kidney", "Heart", "Liver", "Diabetes", "Anemia"]
        
        for name in button_names:
            button = ctk.CTkButton(
                top_buttons_frame,
                text=name,
                width=120,
                height=40,
                corner_radius=20,
                font=("Arial", 14, "bold"),
                fg_color="#00CD66",
                hover_color="#00B359",
                text_color="black",
                border_width=2,
                border_color="black"
            )
            button.pack(side="left", padx=10, pady=10)
        
        # Add title
        title_label = ctk.CTkLabel(
            content,
            text="VitaGuard AI",
            font=("Garamond", 50, "bold"),
            text_color="#2e7d32"
        )
        title_label.pack(pady=(50, 30))
        
        # Create canvas
        canvas = tk.Canvas(
            content,
            bg='#f1f8e9',
            highlightthickness=0,
            width=800,  # زيادة عرض الكانفاس
            height=900  # زيادة ارتفاع الكانفاس
        )
        canvas.pack(expand=True, padx=20, pady=20)

        try:
            # Load main background image
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
            
            # Resize image
            resized_image = pil_image.resize((700, 800))  # تكبير حجم الصورة
            self.body_image = ImageTk.PhotoImage(resized_image)
            
            # Display background image
            canvas.create_image(
                400,  # تعديل موقع الصورة للمركز الجديد
                450,  # تعديل موقع الصورة للمركز الجديد
                image=self.body_image,
                anchor='center'
            )

            # Add side buttons with nature theme
            button_styles = {
                "CARDIAC HOLOGRAM": {
                    "icon": "🫀",
                    "color": "#66bb6a",
                    "hover": "#43a047"
                },
                "NEURAL DIABETES SCAN": {
                    "icon": "🩺",
                    "color": "#81c784",
                    "hover": "#66bb6a"
                },
                "QUANTUM KIDNEY ANALYSIS": {
                    "icon": "🔬",
                    "color": "#a5d6a7",
                    "hover": "#81c784"
                },
                "FULL BODY SCAN": {
                    "icon": "👤",
                    "color": "#c8e6c9",
                    "hover": "#a5d6a7"
                }
            }

            # Add buttons to right frame
            for test_name, style in button_styles.items():
                button = ctk.CTkButton(
                    top_buttons_frame,
                    text=f"{style['icon']} {test_name}",
                    width=120,
                    height=40,
                    corner_radius=20,
                    font=("Arial", 14, "bold"),
                    fg_color=style["color"],
                    hover_color=style["hover"],
                    text_color="black",
                    border_width=2,
                    border_color="black",
                command=lambda t=test_name: self.show_test_details(t)
                )
                button.pack(side="left", padx=10, pady=10)

            # Load and create the four button images on the body
            button_paths = [
                r"C:\Users\11\Desktop\work\dr\x\1.png",
                r"C:\Users\11\Desktop\work\dr\x\2.png",
                r"C:\Users\11\Desktop\work\dr\x\3.png",
                r"C:\Users\11\Desktop\work\dr\x\4.png",
                r"C:\Users\11\Desktop\work\dr\x\5.png"  # إضافة الصورة الخامسة
            ]
            
            # تعريف أسماء الفحوصات لكل زر
            test_names = [
                "CARDIAC HOLOGRAM",
                "NEURAL DIABETES SCAN",
                "QUANTUM KIDNEY ANALYSIS",
                "FULL BODY SCAN",
                "LIVER SCAN"  # إضافة اسم الفحص الخامس
            ]
            
            self.button_images = []
            self.button_images_large = []
            button_positions = [
                (410, 390),  # موقع القلب
                (450, 485),  # موقع البنكرياس
                ( 380,500),  # موقع الكلى
                (390, 450),  # موقع فحص الجسم
                (245, 450)   # موقع الكبد
            ]

            for i, path in enumerate(button_paths):
                # Load original size with different sizes for heart
                img = Image.open(path)
                if i == 0:  # للقلب
                    img = img.resize((120, 120))
                elif i == 1:  # للبنكرياس
                    img = img.resize((80, 80))
                elif i == 2:  # للكلى
                    img = img.resize((120, 45))
                elif i == 3:  # لفحص الجسم
                    img = img.resize((40, 40))
                else:  # للكبد
                    img = img.resize((60, 60))  # حجم الزر الخامس
                photo = ImageTk.PhotoImage(img)
                self.button_images.append(photo)

                # Load larger size for hover
                if i == 0:  # للقلب
                    img_large = img.resize((200, 200))
                elif i == 1:  # للبنكرياس
                    img_large = img.resize((100, 100))
                elif i == 2:  # للكلى
                    img_large = img.resize((150, 70))
                elif i == 3:  # لفحص الجسم
                    img_large = img.resize((60, 60))
                else:  # للكبد
                    img_large = img.resize((90, 90))  # حجم التكبير للزر الخامس
                photo_large = ImageTk.PhotoImage(img_large)
                self.button_images_large.append(photo_large)

                # Create button on canvas
                button_id = canvas.create_image(
                    button_positions[i][0],
                    button_positions[i][1],
                    image=self.button_images[i],
                    tags=f"button_{i}"
                )

                # Bind hover events
                canvas.tag_bind(f"button_{i}", '<Enter>', 
                    lambda e, idx=i: self.on_button_hover(e, canvas, idx))
                canvas.tag_bind(f"button_{i}", '<Leave>', 
                    lambda e, idx=i: self.on_button_leave(e, canvas, idx))
                canvas.tag_bind(f"button_{i}", '<Button-1>', 
                    lambda e, test=test_names[i]: self.show_test_details(test))

        except Exception as e:
            print(f"Error: {str(e)}")
            error_label = ctk.CTkLabel(
                canvas,
                text=f"Error loading image:\n{str(e)}",
                text_color="#ff0000",
                font=("Arial Black", 16)
            )
            error_label.pack(expand=True)

    def show_test_details(self, test_type):
        # Clear previous widgets
        for widget in self.main_frame.winfo_children():
            widget.destroy()
            
        # Create container
        container = ctk.CTkFrame(
            self.main_frame,
            fg_color="#f5f9f0",
            corner_radius=30,
            border_width=2,
            border_color="#558b2f"
        )
        container.pack(expand=True, fill="both", padx=30, pady=30)
        
        # Back button
        back_button = ctk.CTkButton(
            container,
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
        back_button.pack(anchor="nw", padx=30, pady=30)
        
        # Title
        title = ctk.CTkLabel(
            container,
            text=test_type,
            font=("Garamond", 36, "bold"),
            text_color="#2e7d32"
        )
        title.pack(pady=20)
        
        # Parameters panel
        panel = ctk.CTkFrame(
            container,
            corner_radius=20,
            fg_color="#f1f8e9",
            border_width=1,
            border_color="#558b2f"
        )
        panel.pack(pady=20, padx=40, fill="x")
        
        # Add parameters based on test type
        params = {
            "NEURAL DIABETES SCAN": ["Glucose Level", "Blood Pressure", "Insulin", "BMI"],
            "QUANTUM KIDNEY ANALYSIS": ["Creatinine", "GFR", "Albumin", "Electrolytes"],
            "CARDIAC HOLOGRAM": ["Heart Rate", "Blood Pressure", "ECG", "Cholesterol"]
        }
        
        current_params = params.get(test_type, ["Parameter 1", "Parameter 2", "Parameter 3", "Parameter 4"])
        
        for param in current_params:
            param_frame = ctk.CTkFrame(panel, fg_color="transparent")
            param_frame.pack(pady=12, padx=30, fill="x")
            
            label = ctk.CTkLabel(
                param_frame,
                text=param,
                font=("Garamond", 14, "bold"),
                text_color="#2e7d32"
            )
            label.pack(side="left", padx=15)
            
            entry = ctk.CTkEntry(
                param_frame,
                placeholder_text=f"Enter {param}",
                width=350,
                height=40,
                corner_radius=10,
                border_color="#558b2f",
                fg_color="#ffffff",
                text_color="#1b5e20",
                placeholder_text_color="#81c784"
            )
            entry.pack(side="right", padx=15)
        
        # Analyze button
        analyze_button = ctk.CTkButton(
            container,
            text="ANALYZE",
            font=("Garamond", 20, "bold"),
            width=400,
            height=70,
            corner_radius=15,
            fg_color="#66bb6a",
            hover_color="#43a047",
            border_width=2,
            border_color="#2e7d32",
            text_color="#1b5e20",
            command=lambda: self.show_results(test_type)
        )
        analyze_button.pack(pady=40)

    def show_results(self, test_type):
        # Create results window
        results = ctk.CTkToplevel(self.root)
        results.title("Analysis Results")
        results.geometry("1200x800")
        results.configure(fg_color="#e8f5e9")
        
        # Create main container with two frames
        container = ctk.CTkFrame(
            results,
            fg_color="#f1f8e9",
            corner_radius=0
        )
        container.pack(expand=True, fill="both", padx=20, pady=20)
        
        # Left frame for image
        image_frame = ctk.CTkFrame(
            container,
            fg_color="#f5f9f0",
            corner_radius=30,
            border_width=2,
            border_color="#558b2f"
        )
        image_frame.pack(side="left", expand=True, fill="both", padx=(20, 10), pady=20)
        
        # Right frame for results
        results_frame = ctk.CTkFrame(
            container,
            fg_color="#f5f9f0",
            corner_radius=30,
            border_width=2,
            border_color="#558b2f"
        )
        results_frame.pack(side="right", fill="y", padx=(10, 20), pady=20, ipadx=20)
        
        # Title
        title = ctk.CTkLabel(
            image_frame,
            text=f"{test_type}\nANALYSIS RESULTS",
            font=("Garamond", 36, "bold"),
            text_color="#2e7d32"
        )
        title.pack(pady=20)
        
        # Create canvas for body image
        canvas = tk.Canvas(
            image_frame,
            bg='#f5f9f0',
            highlightthickness=0,
            width=800,
            height=900
        )
        canvas.pack(expand=True, padx=20, pady=20)

        try:
            # Load and display background image
            pil_image = Image.open(r"C:\Users\11\Desktop\x.png")
            resized_image = pil_image.resize((700, 800))
            self.body_image = ImageTk.PhotoImage(resized_image)
            canvas.create_image(400, 350, image=self.body_image, anchor='center')

            # تحديد نتائج عشوائية للتجربة (يمكنك تغييرها حسب النتائج الحقيقية)
            results_data = {
                "CARDIAC HOLOGRAM": {
                    "position": (300, 210),
                    "status": "warning",  # يمكن أن تكون "normal" أو "warning"
                    "message": "⚠️ Attention Required\nHigh blood pressure detected"
                },
                "NEURAL DIABETES SCAN": {
                    "position": (320, 260),
                    "status": "normal",
                    "message": "✅ Normal\nAll parameters within range"
                },
                "QUANTUM KIDNEY ANALYSIS": {
                    "position": (280, 270),
                    "status": "warning",
                    "message": "⚠️ Attention Required\nElevated creatinine levels"
                },
                "FULL BODY SCAN": {
                    "position": (290, 250),
                    "status": "normal",
                    "message": "✅ Normal\nNo issues detected"
                }
            }

            # إضافة المؤشرات والرسائل على الصورة
            for test, data in results_data.items():
                # إنشاء دائرة ملونة للمؤشر
                x, y = data["position"]
                color = "#2e7d32" if data["status"] == "normal" else "#d32f2f"
                
                # رسم دائرة كبيرة شفافة للخلفية
                canvas.create_oval(
                    x-30, y-30, x+30, y+30,
                    fill=color if data["status"] == "warning" else "#e8f5e9",
                    outline=color,
                    width=2,
                    tags=f"indicator_{test}"
                )
                
                # إضافة رمز الحالة
                canvas.create_text(
                    x, y,
                    text="✓" if data["status"] == "normal" else "!",
                    fill=color,
                    font=("Arial Black", 20, "bold"),
                    tags=f"symbol_{test}"
                )

                # Bind hover events
                def show_message(e, msg=data["message"], x=x, y=y):
                    # إنشاء خلفية للرسالة
                    bbox = canvas.create_rectangle(
                        x-100, y-45, x+100, y-5,
                        fill="#f5f9f0",
                        outline="#558b2f",
                        tags="tooltip_bg"
                    )
                    # إضافة النص
                    canvas.create_text(
                        x, y-25,
                        text=msg,
                        fill="#2e7d32",
                        font=("Garamond", 12, "bold"),
                        justify="center",
                        tags="tooltip"
                    )

                def hide_message(e):
                    canvas.delete("tooltip")
                    canvas.delete("tooltip_bg")

                canvas.tag_bind(f"indicator_{test}", "<Enter>", show_message)
                canvas.tag_bind(f"indicator_{test}", "<Leave>", hide_message)
                canvas.tag_bind(f"symbol_{test}", "<Enter>", show_message)
                canvas.tag_bind(f"symbol_{test}", "<Leave>", hide_message)

            # Add summary to right frame
            summary_title = ctk.CTkLabel(
                results_frame,
                text="Summary Report",
                font=("Garamond", 24, "bold"),
                text_color="#2e7d32"
            )
            summary_title.pack(pady=20)

            for test, data in results_data.items():
                status_frame = ctk.CTkFrame(
                    results_frame,
                    fg_color="#ffffff",
                    corner_radius=10,
                    border_width=1,
                    border_color="#558b2f"
                )
                status_frame.pack(pady=10, padx=20, fill="x")

                icon = "✅" if data["status"] == "normal" else "⚠️"
                color = "#2e7d32" if data["status"] == "normal" else "#d32f2f"
                
                status_label = ctk.CTkLabel(
                    status_frame,
                    text=f"{icon} {test}",
                    font=("Garamond", 14, "bold"),
                    text_color=color
                )
                status_label.pack(pady=10, padx=10)

            # Close button
            close_button = ctk.CTkButton(
                results_frame,
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

        except Exception as e:
            print(f"Error: {str(e)}")
            error_label = ctk.CTkLabel(
                canvas,
                text=f"Error loading image:\n{str(e)}",
                text_color="#d32f2f",
                font=("Garamond", 16)
            )
            error_label.pack(expand=True)

    def on_button_hover(self, event, canvas, button_index):
        # تكبير الزر عند تمرير المؤشر
        canvas.itemconfig(f"button_{button_index}", image=self.button_images_large[button_index])

    def on_button_leave(self, event, canvas, button_index):
        # إعادة الزر للحجم الطبيعي
        canvas.itemconfig(f"button_{button_index}", image=self.button_images[button_index])

    def run(self):
        self.root.mainloop()

if __name__ == "__main__":
    app = NeonHealthApp()
    app.run()