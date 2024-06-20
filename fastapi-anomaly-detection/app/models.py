from sqlalchemy import Column, Integer, Float, DateTime
from .database import Base

class PembacaanSensor(Base):
    __tablename__ = "pembacaan_sensors"
    id = Column(Integer, primary_key=True, index=True)
    energi = Column(Float, nullable=False)
    berat = Column(Float, nullable=False)
    created_at = Column(DateTime, nullable=False)
    updated_at = Column(DateTime, nullable=False)
    deleted_at = Column(DateTime)
