const mysql = require('mysql2/promise');
const {createTables, generateHotel, resetPostId, generatePost, writeUser, writeUserMeta, writePost, writePostMeta} = require("./functions");
const {faker} = require("@faker-js/faker");

const itemNumbers = {
  hotels : 200,
  reviewsPerHotel: 200,
  roomsPerHotel: 10,
  orders : 3000,
  posts: 3000,
  pages: 1000,
};

async function main() {
  const db = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'root',
    database: 'tp'
  });

  await createTables(db);

  let promises = [];

  // hotels
  console.log('Generating Hotels...');
  for (let i = 0; i < itemNumbers.hotels; i++) {

    let gps;
    // Toute la france
    if (i < 50) {
      gps = faker.address.nearbyGPSCoordinate([47.1129266, 2.5264918], 350, true);
    }
    // Paris
    else if (i < 150) {
      gps = faker.address.nearbyGPSCoordinate([48.859, 2.347], 20)
    }
    // Nevers
    else if (i < 200) {
      gps = faker.address.nearbyGPSCoordinate([46.99, 3.16], 10, true);
    }
    // Lyon
    else if (i < 250) {
      gps = faker.address.nearbyGPSCoordinate([45.76, 4.83], 20, true);
    }
    // Nantes
    else {
      gps = faker.address.nearbyGPSCoordinate([47.21, -1.55], 15, true);
    }

    const generated = await generateHotel(
      i + 1,
      gps
    );

    promises.push(...[
      // user
      writeUser(db, generated.wp_users),

      // usermeta
      ...generated.wp_usermeta.map((data) => writeUserMeta(db, data)),

      // posts
      ...generated.wp_posts.map((data) => writePost(db, data)),

      // postmeta
      ...generated.wp_postmeta.map((data) => writePostMeta(db, data)),
    ]);
  }
  console.log('Inserting Hotels...');
  await Promise.all(promises);
  promises = [];
  console.log('Hotels inserted !');

  // pages
  console.log('Generating Pages...');
  for (let i = 0; i < itemNumbers.pages; i++) {
    const generated = generatePost({post_type: "page"});
    promises.push(writePost(db, generated));
  }
  console.log('Inserting pages...');
  await Promise.all(promises);
  promises = [];
  console.log('Pages inserted !');

  // posts
  console.log('Generating Posts...');
  for (let i = 0; i < itemNumbers.posts; i++) {
    const generated = generatePost({post_type: "post"});
    promises.push(writePost(db, generated));
  }
  console.log('Inserting posts...');
  await Promise.all(promises);
  promises = [];
  console.log('Posts inserted !');

  // commandes
  console.log('Generating Orders...');
  for (let i = 0; i < itemNumbers.orders; i++) {
    const generated = generatePost({post_type: "order"});
    promises.push(writePost(db, generated));
  }
  console.log('Inserting orders...');
  await Promise.all(promises);
  promises = [];
  console.log('Orders inserted !');

  await Promise.all(promises);
}


main()
  .then(() => process.exit(0));
