/*global module:false*/
module.exports = function(grunt) {

	//Project configuration.
	grunt.initConfig({

		//Metadata.
		pkg: grunt.file.readJSON('package.json'),

		//CLEANUP
		clean: {
			composer: [
				'lib/vendor/**/*.md',
				'lib/vendor/**/.*.yml',
				'lib/vendor/**/.gitignore',
				'lib/vendor/**/.gitattributes',
				'lib/vendor/**/build.xml',
				'lib/vendor/**/composer.json',
				'lib/vendor/**/composer.lock',
				'lib/vendor/**/examples',
				'lib/vendor/**/phpunit.*',
				'lib/vendor/**/Tests',
				'lib/vendor/**/tests',
				'lib/vendor/**/.git'
			]
		},

		//PHP REVIEW
		blobphp: {
			check: {
				src: process.cwd(),
				options: {
					colors: true,
					warnings: true
				}
			},
			fix: {
				src: process.cwd(),
				options: {
					fix: true
				},
			}
		},

		//WATCH
		watch: {
			cleanup: {
				files: [
					'lib/vendor/**/*.md',
					'lib/vendor/**/.*.yml',
					'lib/vendor/**/.gitignore',
					'lib/vendor/**/.gitattributes',
					'lib/vendor/**/build.xml',
					'lib/vendor/**/composer.json',
					'lib/vendor/**/composer.lock',
					'lib/vendor/**/examples',
					'lib/vendor/**/phpunit.*',
					'lib/vendor/**/Tests',
					'lib/vendor/**/tests',
					'lib/vendor/**/.git'
				],
				tasks: ['clean', 'notify:cleanup'],
			},
			php: {
				files: [
					'**/*.php',
					'.core/**/*.php'
				],
				tasks: ['php'],
				options: {
					spawn: false
				},
			}
		},

		//NOTIFY
		notify: {
			cleanup: {
				options: {
					title: "Composer garbage cleaned",
					message: "grunt-clean has successfully run"
				}
			}
		}
	});

	//These plugins provide necessary tasks.
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-notify');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-blobfolio');

	grunt.registerTask('php', ['blobphp:check']);

	grunt.event.on('watch', function(action, filepath, target) {
		grunt.log.writeln(target + ': ' + filepath + ' has ' + action);
	});
};
