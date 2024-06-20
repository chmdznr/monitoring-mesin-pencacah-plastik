from pydantic import BaseModel
from datetime import datetime

class AnomalyDetectionResponse(BaseModel):
    is_anomaly: bool
    id: int
    value: float
    created_at: datetime
