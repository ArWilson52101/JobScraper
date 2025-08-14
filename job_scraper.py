import requests
import mysql.connector
from datetime import datetime
import urllib3
import time
# keywords for jobs
KEYWORDS = ['python']#lists all python jobs.

DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': 'mysql', 
    'database': 'final'
}

# seperate library to disable SSL warnings to get around some websites
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

# connects to database
conn = mysql.connector.connect(**DB_CONFIG)
cursor = conn.cursor()

# job scraping code below! beware
url = "https://remoteok.com/api" #using remoteok for the list because i want a remote job. doesnt follow their API TOS but yknow, who's gonna tell on me!

response = requests.get(url, headers={'User-Agent': 'Mozilla/5.0'}, verify=False)

try:
    data = response.json()
except Exception as e:
    print("Error decoding JSON:", e)
    print("Response content:", response.text)
    exit()

# skips the first entry because its usually not a job listing depending on website
for job in data[1:]:
    title = job.get('position', '') or job.get('title', '') #some websites use position some use title.
    company = job.get('company', '')
    url = job.get('url', '')
    description = job.get('description', '')
    date_posted = job.get('date', '')[:10]
    source = "RemoteOK"
    
    location = job.get('location', '').lower()#gets location

    # Filter for US based jobs (not an issue with indeed but still a good thing to do for other websites.)
    if not any(loc in location for loc in ['united states', 'usa', 'us','remote']):
        continue

    # filters for the set keywords
    if not any(keyword.lower() in (title + description).lower() for keyword in KEYWORDS):
        continue


    try:#inserts job info into the job table and excepts iif theres an error.
        cursor.execute("""
            INSERT INTO jobs (title, company, url, description, date_posted, source,location)
            VALUES (%s, %s, %s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE title=VALUES(title)
        """, (title, company, url, description, date_posted, source,location))
    except mysql.connector.Error as err:
        print(f"Database Error: {err}")

#commits and closes the sql queries.
conn.commit()
cursor.close()
conn.close()
print("Scraping completed. refresh the page.")
