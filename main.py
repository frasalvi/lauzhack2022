"""
Flask main page
# To activate environemnt:
conda activate /scratch/lauzhack/DHackers

# To launch flask:
flask --app server.py --debug run --host 0.0.0.0 --port 5001

# Flask app
http://10.90.38.15:5001


"""
try:
  import googleclouddebugger
  googleclouddebugger.enable(
    breakpoint_enable_canary=True
  )
except ImportError:
  pass
from flask import Flask, render_template, request, redirect, url_for 
import psycopg2
import psycopg2.extras
# import numpy as np
# import pandas as pd

DEFAULT_USER = "Giacomo Orsi"


host = '35.241.240.106'
dbname = 'postgres'
user = 'postgres'
pwd = 'Lauzhack2022'
port = 5432
conn = psycopg2.connect("host='{}' port={} dbname='{}' user={} password={}".format(host, port, dbname, user, pwd))

# Dictionary of EPFL buildings 
buildings = {
    "BC 05": (46.51864827968055, 6.561728166020622),
    "BC 06": (46.5185675219631, 6.5617473268174535),
    "BC 01": (46.51879743407615, 6.562087432821845),
    "INM 085": (46.51856421934269, 6.563153921384541),
    "CO 1": (46.520123507543374, 6.565359744765215),
    "SG 1": (46.520932016028915, 6.564014268541558)
}



app = Flask(__name__)

@app.route("/")
def home():
    return render_template("home.html")

@app.route("/home")
def home2():
    return render_template("home.html")

@app.route("/feed")
def feed():
    sql = "SELECT * from issue_db;"
    cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    cursor.execute(sql)

    data = cursor.fetchall()
    cursor.close()

    return render_template("feed.html", data=data[::-1])

@app.route("/newIssue")
def newIssue():
    return render_template("newIssue.html", buildings=buildings)


"""Handle the data of the HTML form and insert it into the database"""
@app.route("/newIssue", methods=['POST'])
def newIssue2():
    DEFAULT_STATUS = 'received'

    # Get the data from the form
    title = request.form['title']
    description = request.form['description']
    room = request.form['room']
    if room == "no-room" : 
        room = None
    solution = request.form['solution']
    # get the location
    location = buildings.get(room, (None, None))

    default_date = "04/12/2022"

    # Insert the data into the database
    sql = "INSERT INTO issue_db (title, description, room, solution, status, upvotes, has_upvoted, user_name, latitude, longitude, timestamp) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);"
    cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    cursor.execute(sql, (title, description, room, solution, DEFAULT_STATUS, 0, False, DEFAULT_USER, location[0], location[1], default_date))
    conn.commit()

    # get the issue id of the newly created issue
    sql = "SELECT issue_id FROM issue_db WHERE title = %s;"
    cursor.execute(sql, (title,))
    issue_id = cursor.fetchone()['issue_id']
    cursor.close()
    # redirect to the issue page
    return redirect(url_for('visualizeIssue', issue_id=str(issue_id)))
    


@app.route("/statistics")
def statistics():
    return render_template("statistics.html")

#/<bool:has_liked>
@app.route("/visualizeIssue/<string:issue_id>")
def visualizeIssue(issue_id):
    sql_issue = f"SELECT * from issue_db WHERE issue_id='{str(issue_id)}';"
    cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)

    cursor.execute(sql_issue)

    # try:
    #     cursor.execute(sql_issue)
    # except Exception as e:
    #     conn = psycopg2.connect("host='{}' port={} dbname='{}' user={} password={}".format(host, port, dbname, user, pwd))
    #     cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    #     cursor.execute(sql_issue)


    issueData = cursor.fetchall()
    cursor.close()
    if (len(issueData) == 0):
        return "Issue not found"
    return render_template("visualizeIssue.html", issueData = issueData[0])


@app.route("/like/<string:issue_id>")
def like(issue_id):
    #if the has_upvoted feature in the database is true, decrease the like count by 1, otherwise increase it by 1
    sql_like = f"SELECT * from issue_db WHERE issue_id='{str(issue_id)}';"
    cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    cursor.execute(sql_like)

    # try:
    #     cursor.execute(sql_like)
    # except Exception as e:
    #     conn = psycopg2.connect("host='{}' port={} dbname='{}' user={} password={}".format(host, port, dbname, user, pwd))
    #     cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    #     cursor.execute(sql_like)
    
    issueData = cursor.fetchall()
    if issueData[0]['has_upvoted'] == True:
        sql_like = f"UPDATE issue_db SET upvotes = upvotes - 1 WHERE issue_id='{str(issue_id)}';"
        cursor.execute(sql_like)
        conn.commit()
        sql_like = f"UPDATE issue_db SET has_upvoted = false WHERE issue_id='{str(issue_id)}';"
        cursor.execute(sql_like)
        conn.commit()
    else:
        sql_like = f"UPDATE issue_db SET upvotes = upvotes + 1 WHERE issue_id='{str(issue_id)}';"
        cursor.execute(sql_like)
        conn.commit()
        sql_like = f"UPDATE issue_db SET has_upvoted = true WHERE issue_id='{str(issue_id)}';"
        cursor.execute(sql_like)
        conn.commit()
    cursor.close()
    
    #automatically redirect to the issue page
    return redirect(url_for('visualizeIssue', issue_id=issue_id))


# Receives post data
@app.route("/update_issue_status", methods=['POST'])
def update_issue_status():
    issue_id = request.form['issue_id']
    new_status = request.form['status']

    cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)    
    sql = f'''UPDATE issue_db
    SET status = '{new_status.lower()}'
    WHERE issue_id = '{issue_id}';
    '''
    cursor.execute(sql)
    conn.commit()
    cursor.close()

    return "Received!" + issue_id + new_status



@app.route("/staff")
def staff():
    sql = "SELECT * from issue_db;"
    cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    cursor.execute(sql)

    data = cursor.fetchall()
    cursor.close()

    return render_template("staff.html", data=data)
    

@app.route("/map")
def map():
    sql = "SELECT * from issue_db;"
    cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    cursor.execute(sql)

    data = cursor.fetchall()
    cursor.close()
    return render_template("map.html", data=data)

if __name__ == '__main__':
    app.run(debug=False, host="104.155.86.18")

