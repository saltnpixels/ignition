const replace = require('replace-in-file')

const fs = require('fs')
const prompts = require('prompts')
const jsonContent = fs.readFileSync('./theme.config.json')
const themeConfig = JSON.parse(jsonContent)

const args = require('minimist')(process.argv)
const dry = args['dry'] //dry mode run wont change anything


// let packageName = new RegExp(`@package ${fromName}`, 'g')
// let lowercaseDash = new RegExp(`${fromSlug}-`, 'g')
// let inAString = new RegExp(`'${fromSlug}'`, 'g');
// let lowerCaseUnderscore = new RegExp(`${fromSlug}_`, 'g')
// let internationalize = new RegExp(`__\\( '${fromName}'`, 'g');


// module.exports = {
//    files: './**/*',
//    ignore: ['./*.md', './*.json', './webpack.config.js','./replace.config.js', './theme.config.json', './node_modules/**', './acf-json/**'],
//    dry: args['dry'] || false,
//    from: [packageName, lowercaseDash, inAString, lowerCaseUnderscore, internationalize, `Theme Name: ${fromName}`, `Theme URI: https://ignition.press/` ],
//    to: [`@package ${themeConfig.name}`, `${themeConfig.slug}-`, `'${themeConfig.slug}'`, `${themeConfig.slug}_`, `__( '${themeConfig.name}'`, `Theme Name: ${themeConfig.name}`],
//    verbose: true
// }


const questions = [
   {
      type: 'text',
      name: 'name',
      message: 'Theme Name'
   },
   {
      type: 'text',
      name: 'slug',
      message: 'Theme-Slug (no-spaces)'
   },
   {
      type: 'text',
      name: 'uri',
      message: 'Theme URI',
      initial: 'https://ignition.press/'
   },
   {
      type: 'text',
      name: 'author',
      message: 'Author',
      initial: 'Eric Greenfield'
   },
   {
      type: 'text',
      name: 'authorUri',
      message: 'Author URI',
      initial: 'https://saltnpixels.com/'
   },
   {
      type: 'text',
      name: 'description',
      message: 'Description',
      initial: 'An amazing theme built with ignition'
   },
   {
      type: 'text',
      name: 'server',
      message: 'Development or Remote Url',
      initial: 'ignition.local'
   }
];

(async () => {

       let allQuestions = true
       const onCancel = prompt => {
          console.log('Theme Setup Aborted')
          allQuestions = false
       }
       const response = await prompts(questions, { onCancel })


       //name and slug and all questions answered must be true
       if (response.name && response.slug && allQuestions) {
          //fixing responses
          response.slug = response.slug.replace(' ', '-').toLowerCase()
          let underscoreSlug = response.slug.replace('-', '_')
          let underscoreOldSlug = themeConfig.slug.replace('-', '_')

          //all the regex patterns for replacement
          let packageName = new RegExp(`@package ${themeConfig.name}`, 'g')
          let lowercaseDash = new RegExp(`${themeConfig.slug}-`, 'g')
          let inAString = new RegExp(`'${themeConfig.slug}'`, 'g')
          let lowerCaseUnderscore = new RegExp(`${underscoreOldSlug}_`, 'g')
          let internationalize = new RegExp(`__\\( '${themeConfig.slug}'`, 'g')


          //replacement options. setup files to replace
          const options = {
             files: './**/*',
             ignore: ['./*.md', './*.json', './webpack.config.js', './replace.config.js', './theme.config.json', './node_modules/**', './acf-json/**'],
             dry: args['dry'] || false,
             from: [packageName, lowercaseDash, inAString, lowerCaseUnderscore, internationalize, /Theme Name: .*/g, /Theme URI: .*/g, /Author: .*/g, /Author URI: .*/g, /Description: .*/g, /Text Domain: .*/g],
             to: [`@package ${response.name}`, `${response.slug}-`, `'${response.slug}'`, `${underscoreSlug}_`, `__( '${response.name}'`, `Theme Name: ${response.name}`, `Theme URI: ${response.uri}`, `Author: ${response.author}`, `Author URI: ${response.authorUri}`, `Description: ${response.description}`, `Text Domain: ${response.slug}`],
             verbose: true
          }

          try {
             const results = await replace(options)
             const changedFiles = results
                 .filter(result => result.hasChanged)
                 .map(result => result.file + '\n')
             console.log(`${changedFiles.length} have changed. \n The following files have changed: \n ${changedFiles.join(' ')}`)
          } catch (error) {
             console.error('Error occurred:', error)
          }

          //second replace. change themes config options
          const optionsConfig = {
             files: './theme.config.json',
             dry: args['dry'] || false,
             verbose: true,
             from: [/"name": ".*/g, /"slug": ".*/g, /"server": ".*/g],
             to: [`"name": "${response.name}",`, `"slug": "${response.slug}",`, `"server": "${response.server}",`]
          }

          try {
             const results = await replace(optionsConfig)
             const changedFiles = results
                 .filter(result => result.hasChanged)
                 .map(result => result.file + '\n')
             console.log('Updated Theme Config')
             console.log('All Done! \n Now you can start creating your theme! \n Check out the theme.config file as well as functions.php file, and the variables.scss files. \n There is where you can set things up a bit further.')
          } catch (error) {
             console.error('Error occurred:', error)
          }


       }
    }
)()
