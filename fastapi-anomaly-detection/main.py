from fastapi import FastAPI, Depends, HTTPException
from sqlalchemy.orm import Session
from sklearn.ensemble import IsolationForest
import pandas as pd
from app import models, schemas
from app.database import SessionLocal, engine

app = FastAPI()

models.Base.metadata.create_all(bind=engine)

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

@app.post("/detect-anomalies", response_model=schemas.AnomalyDetectionResponse)
def detect_anomalies(db: Session = Depends(get_db)):
    sensors = db.query(models.PembacaanSensor).order_by(models.PembacaanSensor.id.desc()).limit(21).all()
    if len(sensors) < 21:
        raise HTTPException(status_code=400, detail="Not enough data points for analysis")

    # Convert to DataFrame
    data = pd.DataFrame([{"id": sensor.id, "energi": sensor.energi, "created_at": sensor.created_at} for sensor in sensors])

    # Separate the latest data point from the previous 20
    latest_data = data.iloc[0]
    previous_data = data.iloc[1:]

    # Fit the Isolation Forest model
    model = IsolationForest(contamination=0.05)
    model.fit(previous_data[['energi']])

    # Predict the anomaly status of the latest data point
    is_anomaly = model.predict([latest_data[['energi']]])

    return {
        "is_anomaly": bool(is_anomaly[0] == -1),
        "id": int(latest_data["id"]),
        "value": float(latest_data["energi"]),
        "created_at": latest_data["created_at"]
    }
