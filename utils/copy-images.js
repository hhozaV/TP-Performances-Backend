const fs = require('fs/promises');
const path = require('path');

const imagesPath = '/Users/arthur/Downloads/test/unoccluded/';
const destinationPath = '/Users/arthur/Projets/iut-nevers/performance/td-performances-backend/src/assets/images/';
const requiredImages = 100;
let copiedImages = 0;

async function main() {
  for (let i = 20; i < 92; i++) {
    if (copiedImages > requiredImages)
      break;

    try {
      const firstLevelPath = path.join(imagesPath, i.toString());
      const imagePaths = await Promise.all((await fs.readdir(firstLevelPath))
        .filter(dirName => dirName !== ".DS_Store")
        .map(async dirName => {
          const imageName = (await fs.readdir( path.join(firstLevelPath, dirName, 'traffickcam') ) ).filter(n => n !== ".DS_Store")[0];
          return path.join(firstLevelPath, dirName, 'traffickcam', imageName);
        } ));

      await Promise.all( imagePaths.map(async imgPath => {
        return fs.cp(imgPath, path.join( destinationPath, path.basename( imgPath )))
          .then(() => {
            copiedImages++;
            console.log(`Copied (${copiedImages}/${requiredImages}) : ${imgPath}`);
          });
      }) );
    } catch (e) {
      console.log(e);
    }
  }
}


main().then(() => process.exit(0));