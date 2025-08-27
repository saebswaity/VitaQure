import pickle

# Load the model
with open('normalize_heart_2.pkl', 'rb') as file:
    model = pickle.load(file)

# Check if the model has a `predict` function
if hasattr(model, "predict"):
    print("✅ The model has a `predict` function.")
else:
    print("❌ The model does not have `predict`, it might not be trained.")

# Check if the model is trained
if hasattr(model, "named_steps") and "model" in model.named_steps:
    trained_model = model.named_steps["model"]
    
    # Check for trained parameters
    if hasattr(trained_model, "coef_") or hasattr(trained_model, "feature_importances_"):
        print("✅ The model is trained and ready for prediction.")
    else:
        print("❌ The model is not trained, retraining is required.")
else:
    print("❌ No valid model found inside the Pipeline.")
