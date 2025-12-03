
import time, json

try:
    temperature = 24.3
    humidity = 56.7
except Exception as e:
    temperature = None
    humidity = None

print(json.dumps({
    'temperature': temperature,
    'humidity': humidity,
    'timestamp': int(time.time())
}))