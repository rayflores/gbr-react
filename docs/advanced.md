# Advanced WordPress Assessment

This is the assessment for incoming developers aimed toward gauging more advanced experience with WordPress combined with a JavaScript-centric front-end.

Please follow the steps below and turn it in to us when you are done!

> NOTE: Anything marked as 'BONUS' is **NOT** required and is only there if you feel like showing off. That being said, feel free to show off. Have fun with it!

## Setup

Visit our [README](../README.md#Setup) for setup instructions.

## Steps

- [ ] Clone or fork this repo, then establish a public repository where your code will live and can be viewed by us.
- [ ] Create 5 sample posts and 5 pages inside WordPress.
  - Bonus points: Use wp-cli, or some automated way to do this.
- [ ] Create a custom "Movie" post type and create 10 sample Movie posts.
  - [ ] Create a custom "Genre" taxonomy and attach it to the `movie` post type only.
- [ ] Create a React app with a standard header w/ nav linking to pages, a standard footer, which talks to WordPress REST API. Create the following pages in the React app:
  - [ ] Homepage - 5 Movie Posts
    - Each post should have a featured image, an excerpt and a link to the movie single post page.
    - Bonus points: infinite scroll or pagination.
  - [ ] Single Movie Post (Featured image, title, genre, full text)
    - Bonus points: Showcase other movies.
  - [ ] Single Post (Featured image, author, title, text)
  - [ ] Single Page (Can be just title, author and text)
  - Bonus points: Hot or live refresh.
- [ ] Add SCSS compiling in and style your React app.
  - Bonus points: Uglify your JavaScript/SCSS build
- [ ] Add instructions on requirements, installation and running everything to your README file.


## Requirements

Visit our [README](../README.md#Requirements) for requirements.

## Information

Visit our [README](../README.md#Information) for information on @wordpress-env and @wordpress-scripts.

## Common Issues

Visit our [README](../README.md#Common-Issues) for help solving common issues.

## Bonus

- Add a WordPress REST cache plugin for GET requests.