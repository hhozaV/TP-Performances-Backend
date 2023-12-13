const {faker} = require("@faker-js/faker/locale/fr");
const slugify = require("slugify");
const fs = require('fs/promises');
const path = require('path');

const imagesFiles = [];
let imageIndex = -1;

async function getImageNextImage() {
  if (imagesFiles.length < 1) {
    imagesFiles.push(
      ...(await fs.readdir( path.resolve(__dirname, '../src/assets/images' ) ))
        .filter(fileName => fileName !== ".DS_Store")
        .map(imageName => `/assets/images/${imageName}`)
    );
  }

  imageIndex += 1;
  if (!imagesFiles[imageIndex])
    throw new Error('There is no image');

  return imagesFiles[imageIndex];
}

let postId = 0;


function getNextPostId() {
  postId++;
  return postId;
}


function resetPostId() {
  postId = 0;
}


/**
 *
 * @param {number} id
 * @param gps
 */
async function generateHotel(id, gps) {
  /**
   * @type {import('@faker/faker').SexType} sexType
   */
  const sexType = (id % 2 === 0) ? 'female' : 'male';
  const firstName = faker.name.firstName(sexType);
  const lastName = faker.name.lastName(sexType);
  const userName = `${firstName}_${lastName}`;
  const cityName = faker.address.cityName();
  const hotelName = faker.company.name();
  gps = gps ?? faker.address.nearbyGPSCoordinate([47.1129266, 2.5264918], 350, true);

  const postMetas = [];
  const posts = [];

  // Rooms
  for (let i = 0; i < 6; i++) {
    const postId = getNextPostId();

    posts.push(generatePost({
      ID: postId,
      post_author: id,
      post_title: `Chambre n°${i + 1}`,
      post_name: `room-${i + 1}`,
      guid: `http://localhost/${slugify(hotelName)}/rooms/room-${i + 1}`,
      post_type: "room",
    }));

    postMetas.push(...[
      {
        post_id: postId,
        meta_key: "price",
        meta_value: faker.commerce.price((i + 1) * 50, (i + 1) * 100)
      },
      {
        post_id: postId,
        meta_key: "coverImage",
        meta_value: faker.image.unsplash.objects()
      },
      {
        post_id: postId,
        meta_key: "bedrooms_count",
        meta_value: faker.datatype.number({min: i + 1, max: i + 6})
      },
      {
        post_id: postId,
        meta_key: "bathrooms_count",
        meta_value: faker.datatype.number({min: i + 1, max: i + 4})
      },
      {
        post_id: postId,
        meta_key: "surface",
        meta_value: faker.datatype.number({min: (i + 1) * 40, max: (i + 1) * 80})
      },
      {
        post_id: postId,
        meta_key: "type",
        meta_value: ['Maison', 'Appartement', 'Chambre'][faker.datatype.number({min: 0, max: 2})]
      },
    ]);
  }

  // Reviews
  const reviewsCount = faker.datatype.number({min: 75, max: 125});
  for (let i = 0; i < reviewsCount; i++) {
    const postId = getNextPostId();

    posts.push(generatePost({
      ID: postId,
      post_author: id,
      post_title: `${faker.lorem.words(6)}, from ${faker.name.firstName()}`,
      post_type: "review",
    }));

    postMetas.push({
      post_id: postId,
      meta_key: "rating",
      meta_value: faker.datatype.number({min: 0, max: 10})
    });
  }

  return {
    wp_users: {
      id: id,
      user_login: userName.toLowerCase(),
      user_pass: faker.internet.password(),
      user_nicename: userName.toLowerCase(),
      user_email: faker.internet.email(firstName, lastName),
      user_registered: formatDateToSQLFormat(faker.date.past()),
      user_activation_key: null,
      user_status: "ACTIVE",
      display_name: hotelName,
    },
    wp_usermeta: [
      {
        key: "address_1",
        value: faker.address.streetAddress(),
      },
      {
        key: "address_2",
        value: `Bâtiment ${faker.address.buildingNumber()}`
      },
      {
        key: "address_city",
        value: cityName,
      },
      {
        key: "address_zip",
        value: faker.address.zipCode(),
      },
      {
        key: "address_country",
        value: "France",
      },
      {
        key: "geo_lat",
        value: gps[0],
      },
      {
        key: "geo_lng",
        value: gps[1],
      },
      {
        key: "phone",
        value: faker.phone.number()
      },
      {
        key: "email",
        value: faker.internet.email(hotelName, undefined, cityName + '.com')
      },
      {
        key: "coverImage",
        value: await getImageNextImage()
      }
    ].map(data => ({
      user_id: id,
      meta_key: data.key,
      meta_value: data.value
    })),
    wp_posts: posts,
    wp_postmeta: postMetas
  };
}


/**
 *
 * @param {import('mysql2').Connection} db
 */
async function createTables(db) {
  await Promise.all([
    db.execute(`CREATE TABLE IF NOT EXISTS \`wp_users\`
                (
                    \`ID\`                  bigint(255) NOT NULL AUTO_INCREMENT,
                    \`user_login\`          LONGTEXT    NOT NULL DEFAULT '',
                    \`user_pass\`           LONGTEXT    NOT NULL DEFAULT '',
                    \`user_nicename\`       LONGTEXT    NOT NULL DEFAULT '',
                    \`user_email\`          LONGTEXT    NOT NULL DEFAULT '',
                    \`user_url\`            LONGTEXT    NOT NULL DEFAULT '',
                    \`user_registered\`     DATETIME    NOT NULL DEFAULT '0000-00-00 00:00:00',
                    \`user_activation_key\` LONGTEXT    NULL     DEFAULT NULL,
                    \`user_status\`         LONGTEXT    NOT NULL DEFAULT 0,
                    \`display_name\`        LONGTEXT    NOT NULL DEFAULT '',
                    PRIMARY KEY (\`ID\`)
                ) ENGINE = InnoDB`
    ),

    db.execute(`CREATE TABLE IF NOT EXISTS \`wp_usermeta\`
                (
                    \`umeta_id\`   bigint(255) NOT NULL AUTO_INCREMENT,
                    \`user_id\`    bigint(255) NOT NULL DEFAULT 0,
                    \`meta_key\`   LONGTEXT             DEFAULT NULL,
                    \`meta_value\` LONGTEXT             DEFAULT NULL,
                    PRIMARY KEY (\`umeta_id\`)
                ) ENGINE = InnoDB`),

    db.execute(`CREATE TABLE IF NOT EXISTS \`wp_posts\`
                (
                    \`ID\`                    bigint(255) NOT NULL AUTO_INCREMENT,
                    \`post_author\`           bigint(255) NOT NULL DEFAULT 0,
                    \`post_date\`             datetime    NOT NULL DEFAULT '0000-00-00 00:00:00',
                    \`post_date_gmt\`         datetime    NOT NULL DEFAULT '0000-00-00 00:00:00',
                    \`post_content\`          LONGTEXT    NOT NULL,
                    \`post_title\`            LONGTEXT    NOT NULL,
                    \`post_excerpt\`          LONGTEXT    NULL     DEFAULT NULL,
                    \`post_status\`           LONGTEXT    NOT NULL DEFAULT 'publish',
                    \`comment_status\`        LONGTEXT    NOT NULL DEFAULT 'open',
                    \`ping_status\`           LONGTEXT    NOT NULL DEFAULT 'open',
                    \`post_password\`         LONGTEXT    NULL     DEFAULT NULL,
                    \`post_name\`             LONGTEXT    NOT NULL DEFAULT '',
                    \`to_ping\`               LONGTEXT    NULL     DEFAULT NULL,
                    \`pinged\`                LONGTEXT    NULL     DEFAULT NULL,
                    \`post_modified\`         DATETIME    NOT NULL DEFAULT '0000-00-00 00:00:00',
                    \`post_modified_gmt\`     DATETIME    NOT NULL DEFAULT '0000-00-00 00:00:00',
                    \`post_content_filtered\` LONGTEXT    NULL     DEFAULT NULL,
                    \`post_parent\`           BIGINT(255) NOT NULL DEFAULT 0,
                    \`guid\`                  LONGTEXT    NULL     DEFAULT NULL,
                    \`menu_order\`            BIGINT(255) NOT NULL DEFAULT 0,
                    \`post_type\`             LONGTEXT    NOT NULL DEFAULT 'post',
                    \`post_mime_type\`        LONGTEXT    NULL     DEFAULT NULL,
                    \`comment_count\`         BIGINT(255) NOT NULL DEFAULT 0,
                    PRIMARY KEY (\`ID\`)
                ) ENGINE = InnoDB`
    ),

    db.execute(`CREATE TABLE IF NOT EXISTS \`wp_postmeta\`
                (
                    \`meta_id\`    BIGINT(255) NOT NULL AUTO_INCREMENT,
                    \`post_id\`    BIGINT(255) NOT NULL DEFAULT 0,
                    \`meta_key\`   LONGTEXT             DEFAULT NULL,
                    \`meta_value\` LONGTEXT             DEFAULT NULL,
                    PRIMARY KEY (\`meta_id\`)
                ) ENGINE = InnoDB
    `)
  ]);
}


/**
 *
 * @param {Date} date
 * @returns {string}
 */
function formatDateToSQLFormat(date) {
  return date.toISOString().slice(0, 19).replace('T', ' ');
}


function generatePost(data) {
  const postDate = data.post_date ?? formatDateToSQLFormat(faker.date.past());
  const postTitle = data.post_title ?? faker.lorem.words(4);

  return {
    ID: data ?? getNextPostId(),
    post_author: 0,
    post_date: postDate,
    post_title: postTitle,
    post_date_gmt: postDate,
    post_content: faker.lorem.paragraphs(10),
    post_excerpt: faker.lorem.paragraph(),
    post_status: postId % 4 === 0 ? "draft" : "publish",
    comment_status: "closed",
    ping_status: "closed",
    post_password: null,
    post_name: slugify(postTitle),
    to_ping: null,
    pinged: null,
    post_modified: formatDateToSQLFormat(faker.date.future(null, postDate)),
    post_modified_gmt: formatDateToSQLFormat(faker.date.future(null, postDate)),
    post_content_filtered: null,
    post_parent: 0,
    guid: `http://localhost/${slugify(postTitle)}`,
    menu_order: 0,
    post_type: data.type ?? "post",
    post_mime_type: null,
    comment_count: 0,
    ...data,
  }
}


/**
 *
 * @param {import('mysql2').Connection} db
 * @param {string} table
 * @param {Object} data
 */
function write(db, table, data) {
  const keys = Object.keys(data);
  const placeholders = keys.map(_ => '?');
  const values = Object.values(data);

  return db.query(
    `INSERT INTO tp.${table} (${keys.join(', ')})
     VALUES (${placeholders.join(',')})`,
    values
  );
}


/**
 * @param {import('mysql2').Connection} db
 * @param {Object} data
 */
function writePost(db, data) {
  return write(db, 'wp_posts', data);
}


/**
 * @param {import('mysql2').Connection} db
 * @param {Object} data
 */
function writePostMeta(db, data) {
  return write(db, 'wp_postmeta', data);
}


/**
 * @param {import('mysql2').Connection} db
 * @param {Object} data
 */
function writeUser(db, data) {
  return write(db, 'wp_users', data);
}


/**
 * @param {import('mysql2').Connection} db
 * @param {Object} data
 */
function writeUserMeta(db, data) {
  return write(db, 'wp_usermeta', data);
}


module.exports = {
  getNextPostId,
  generateHotel,
  formatDateToSQLFormat,
  createTables,
  resetPostId,
  generatePost,
  writeUser,
  writeUserMeta,
  writePost,
  writePostMeta
};