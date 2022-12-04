# pip installations - pandas, pymysql, surprise

# Import statements
import pandas as pd
import numpy as np
import pymysql
from surprise import Dataset, Reader, SVD, accuracy, dump
from surprise.model_selection import train_test_split

# Connecting to the database
conn=pymysql.connect(host='localhost',port=int(3309),user='root',passwd='',db='cprs')

# Read Users table
users=pd.read_sql(f"SELECT userID FROM User WHERE isNewUser IS FALSE", conn)
# print(users)

# Read Shows table
shows=pd.read_sql(f"SELECT * FROM Shows", conn)
shows.index=shows.showID
# print(shows[:5])

# Read Language Preferences table
languagePreferences=pd.read_sql(f"SELECT * FROM LanguagePreferences", conn)
# print(languagePreferences)

# Read Platform Preferences table
platformPreferences=pd.read_sql(f"SELECT * FROM PlatformPreferences", conn)
# print(platformPreferences)

# Read Ratings table
ratings=pd.read_sql(f"SELECT * FROM Ratings", conn)
ratings=ratings.drop("ratedAt", axis=1)
# print(ratings)

# Read IMDB's User Ratings from user_ratings.csv
imdb_ratings=pd.read_csv(r"data/user_ratings.csv")
# print(imdb_ratings[:5])

# Keeping only the ratings for title_index matching showIDs in Shows table.
imdb_ratings=imdb_ratings[imdb_ratings.title_index.isin(list(shows.showID))]

# Merge ratings and imdb_ratings.
reviewers=pd.DataFrame({"reviewer":imdb_ratings.reviewer.unique()})
reviewers["reviewer_id"]=reviewers.index
imdb_ratings=pd.merge(imdb_ratings, reviewers)
imdb_ratings=imdb_ratings.drop("reviewer", axis=1)
imdb_ratings.columns=["rating", "contentID", "userID"]
imdb_ratings.userID=imdb_ratings.userID+(ratings.userID.unique().shape[0]+1)
imdb_ratings=pd.concat([ratings, imdb_ratings], ignore_index=True)
# print(ratings[:5])

# Train, test split the data.
reader=Reader(rating_scale=(1, 10))
data=Dataset.load_from_df(imdb_ratings[['userID', 'contentID', 'rating']], reader)
train_set, test_set=train_test_split(data, test_size=.20)

# Build the model.
model=SVD(random_state=29) # Default - factors=100, epochs=20

# Train and test the model.
predictions=model.fit(train_set).test(test_set)
# print(accuracy.rmse(predictions))

# Make predictions.
def predictRating(showID, userID):
    return model.predict(uid=userID, iid=showID).est

# Find next 15 shows that each user might like.
for userID in users.userID:
    # Get the list of shows that user has not rated yet.
    showIDs=set(shows.showID)-set(ratings[ratings.userID==userID].contentID)
    
    # Getting user's preferences.
    userLanguages=languagePreferences[languagePreferences.userID==userID]
    userPlatforms=platformPreferences[platformPreferences.userID==userID]

    # Filter shows within user's language preferences.
    showIDs=set(showIDs)&set(shows[shows.languageID.isin(userLanguages.languageID)].showID)
    
    # Filter shows within user's platform preferences.
    _showIDs=set()
    for i in userPlatforms.platformID:
        _showIDs=_showIDs|set(shows[shows.platformIDs.str.contains(f"{i}")].showID)
    showIDs=showIDs&_showIDs

    # Predict ratings for all these shows.
    _ratings=shows.loc[shows.showID.isin(showIDs), "showID"].apply(predictRating, args=(userID, ))
    
    # Sort by rating, get total 30 results.
    _ratings=pd.DataFrame(_ratings.sort_values(ascending=False)[:30])
    _ratings.columns=["rating"]
    # print(_ratings)

    with conn.cursor() as cursor:
        # Delete this user's entries from PersonalizedRecommendations table.
        sql=f"DELETE FROM PersonalizedRecommendations WHERE userID={userID};"
        cursor.execute(sql)

        # Add new recommendations to PersonalizedRecommendations table.
        for showID in _ratings.index:
            sql=f"INSERT INTO PersonalizedRecommendations VALUES ({userID}, {showID});"
            cursor.execute(sql)
    conn.commit()

    