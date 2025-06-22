import pandas as pd
import numpy as np
from sklearn.ensemble import IsolationForest
from sklearn.preprocessing import StandardScaler, LabelEncoder
import matplotlib.pyplot as plt
import seaborn as sns

df = pd.read_csv('data/user_sessions.csv')


df['login_time'] = pd.to_datetime(df['login_time'], errors='coerce')
df['logout_time'] = pd.to_datetime(df['logout_time'], errors='coerce')

df['login_hour'] = df['login_time'].dt.hour
df['day_of_week'] = df['login_time'].dt.dayofweek


df['session_duration'] = pd.to_numeric(df['session_duration'], errors='coerce').fillna(0)

df['ip_address'] = df['ip_address'].fillna('unknown')
df['user_agent'] = df['user_agent'].fillna('unknown')

ip_encoder = LabelEncoder()
ua_encoder = LabelEncoder()

df['ip_encoded'] = ip_encoder.fit_transform(df['ip_address'])
df['ua_encoded'] = ua_encoder.fit_transform(df['user_agent'])

df['login_count'] = df['user_id'].map(df['user_id'].value_counts())

print(" Data Overview:")
print(df.info())

print("\n Basic Statistics:")
print(df.describe(include='all'))

print("\n Missing Values:")
print(df.isnull().sum())

print("\n Top User Agents:")
print(df['user_agent'].value_counts().head())

print("\n IP Address Distribution:")
print(df['ip_address'].value_counts().head())

plt.figure(figsize=(12, 5))
df_sorted = df.sort_values(by='login_time')
plt.plot(df_sorted['login_time'], df_sorted['session_duration'], label='Session Duration')
plt.title("Session Duration Over Time")
plt.xlabel("Login Time")
plt.ylabel("Session Duration (s)")
plt.grid(True)
plt.tight_layout()
plt.show()

plt.figure(figsize=(12, 5))
df.set_index('login_time').resample('D').size().plot()
plt.title("Login Count Per Day")
plt.ylabel("Logins")
plt.xlabel("Date")
plt.grid(True)
plt.tight_layout()
plt.show()

plt.figure(figsize=(10, 6))
sns.heatmap(df[['session_duration', 'login_hour', 'day_of_week', 'ip_encoded', 'ua_encoded', 'login_count']].corr(),
            annot=True, cmap='coolwarm')
plt.title("Correlation Heatmap")
plt.tight_layout()
plt.show()

features = df[['session_duration', 'login_hour', 'day_of_week', 'ip_encoded', 'ua_encoded', 'login_count']]

scaler = StandardScaler()
X_scaled = scaler.fit_transform(features)

clf = IsolationForest(n_estimators=100, contamination=0.1, random_state=42)
clf.fit(X_scaled)

df['anomaly_score'] = clf.decision_function(X_scaled)
df['is_anomaly'] = pd.Series(clf.predict(X_scaled)).map({1: 0, -1: 1})

print("\n Anomalies Detected:")
print(df[df['is_anomaly'] == 1])

plt.figure(figsize=(12, 5))
plt.scatter(df['login_time'], df['session_duration'],
            c=df['is_anomaly'], cmap='coolwarm', edgecolors='k')
plt.xlabel("Login Time")
plt.ylabel("Session Duration (s)")
plt.title("Anomalies in Session Duration Over Time")
plt.grid(True)
plt.tight_layout()
plt.show()

df.to_csv('user_sessions_with_anomalies.csv', index=False)
print("\n Output saved as: user_sessions_with_anomalies.csv")

