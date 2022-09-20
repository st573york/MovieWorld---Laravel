<h1 align="center">Movie World</h1>

## How to run the application on macOS

Pull image from docker hub:

- docker pull st573york/movieworld

Run image in a new container:

- docker run -itd --privileged -p 80:80 st573york/movieworld:latest /usr/sbin/init

Browse to localhost

## Task

The application named MovieWorld is a social sharing platform where users can share their favorite movies of all time.

Each movie has a title and a small description as well as a date that corresponds to the date it was added to the database.

In addition it holds a reference to the user that submitted it.

Users can also express their opinion by liking, hating or reviewing a movie.

When an unauthorized user visits the application, a list of movies that have been added to the database is displayed, as well as links for log in / sign up. User is able to search/filter any movie by using the search bar.

Each movie contains the following information:

● Title

● Description

● Name of the user 

● Date of publication 

● Number of likes

● Number of hates

● Number of reviews

The number of votes is a link that, when clicked, it shows list of users who have voted a movie.

The number of reviews is a link that, when clicked, it shows list of users who have reviewed a movie.

The list of movies can also be sorted by title, number of likes / hates / reviews, author or most recent / oldest publication date.

In addition, authorized users are able to:
  - Search/filter any movie by using the search bar.
  - Edit their profile by clicking on their username -> profile.
  - Delete their own account by clicking on their username -> delete.
  - Logout from application by clicking on their username -> logout.
  - Add, edit or delete their own movies and express their opinion for other movies by either liking, hating or by retracting their vote. Voting is performed by clicking on the respective links that are displayed for each movie.
  - Add a review for other movies. Reviewing is performed by clicking on the respective link that is displayed for each movie.
