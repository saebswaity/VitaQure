import os
import sys
import json
import joblib
import pandas as pd


def main():
    repo_root = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
    dr_dir = os.path.join(repo_root, 'dr')

    csv_path = os.path.join(dr_dir, 'csv', 'kidney_processed.csv')
    model_path_variants = [
        os.path.join(dr_dir, 'KIDNEY.pkl'),
        os.path.join(dr_dir, 'kidney.pkl')
    ]

    model_path = None
    for p in model_path_variants:
        if os.path.exists(p):
            model_path = p
            break
    if model_path is None:
        print('Model file not found. Expected one of:', model_path_variants, file=sys.stderr)
        sys.exit(1)

    if not os.path.exists(csv_path):
        print('CSV not found at', csv_path, file=sys.stderr)
        sys.exit(1)

    # Load model (pipeline or estimator)
    model = joblib.load(model_path)

    # Read first 5 rows
    df = pd.read_csv(csv_path, nrows=5)

    # Separate features and optional label if present
    label_col = 'Diagnosis'
    feature_df = df.drop(columns=[label_col], errors='ignore')

    # If the saved object is a pipeline with preprocessor + classifier
    proba = None
    try:
        # Many sklearn classifiers expose predict_proba
        if hasattr(model, 'named_steps') and 'preprocessor' in model.named_steps and 'classifier' in model.named_steps:
            preprocessed = model.named_steps['preprocessor'].transform(feature_df)
            proba = model.named_steps['classifier'].predict_proba(preprocessed)[:, 1]
        elif hasattr(model, 'predict_proba'):
            proba = model.predict_proba(feature_df)[:, 1]
        else:
            # Fallback: try decision_function then map to 0..1 via logistic
            if hasattr(model, 'decision_function'):
                import numpy as np
                logits = model.decision_function(feature_df)
                proba = 1 / (1 + np.exp(-logits))
            else:
                raise RuntimeError('Model does not support predict_proba or decision_function')
    except Exception as e:
        print('Inference failed:', str(e), file=sys.stderr)
        sys.exit(1)

    # Apply threshold and print results
    print('index,probability,class')
    for i, p in enumerate(proba):
        cls = 1 if p > 0.5 else 0
        print(f"{i},{p:.6f},{cls}")


if __name__ == '__main__':
    main()

