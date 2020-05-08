module.exports = function(grunt) {
	// include for ES6 Promises for postCss 
	var Promise = require('es6-promise').Promise;

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Maybe move to package.json
		include_files: {
			js: {
				libs: [
					'bower_components/nanoscroller/bin/javascripts/jquery.nanoscroller.js',
					// 'bower_components/responsive-bootstrap-toolkit/dist/bootstrap-toolkit.min.js',
					// 'bower_components/jquery-touchswipe/jquery.touchSwipe.min.js',
				],
				es2015: [
				],
				es2015module: 'js/index.js',
			},
			scss: [
				'scss/screen.scss'
			],
		},

		sass: {
			dist: {
				options: {
					sourcemap: 'none'
					// noCache: true
				},
				files: {
					'<%= pkg.build.css %>/styles.css': '<%= include_files.scss %>',
				}
			},
			dist_compressed: {
				options: {
					// noCache: true,
					style: 'compressed',
					// sourcemap: 'file'
				},
				files: {
					'<%= pkg.build.css %>/styles.min.css': '<%= pkg.build.css %>/styles.css',
				}
			}
		},

		postcss: {
			options: {
				map: true,
				// We need to `freeze` browsers versions for testing purposes.
				// browsers: ['last 2 versions', 'opera 12', 'ie 8', 'ie 9']
				processors: [
					require('pixrem')(),
					require('postcss-sorting')({ /* options */ }),
					require('autoprefixer')({browsers: ['last 2 versions', 'opera 12', 'ie 8', 'ie 9']}),
				]
			},
			// prefix the specified file
			// single_file: {
			// 	src: '<%= pkg.build.css %>/styles.css',
			// 	dest: '<%= pkg.build.css %>/styles.css'
			// }
			// multiple_files: {
			// 	expand: true,
			// 	flatten: true,
			// 	src:  '<%= pkg.build.css %>/*.css',
			// 	dest: '<%= pkg.build.css %>/'
			// },
			dist: {
				src: '../css/*.css'
			}
		},

		clean: [
			'images/_temp'
		], //removes old data
		svgmin: {
			dist: {
				files: [{
					expand: true,
					cwd: 'images/svg',
					src: ['*.svg'],
					dest: 'images/_temp/svg/min',
					ext: '.svg'
				}]
			}
		},
		svgcss: {
			toCrlf: {
				options: {
					csstemplate: 'images/svg-core/scss-map-template2.hbs',
					previewhtml: null,
				},
				files: {
					'scss/sprites/svg-to-css.scss': ['images/_temp/svg/min/*.svg']
				}
			}
		},

		browserify: {
			dist: {
				options: {
					transform: [ ["babelify", { "presets": ["es2015"] }] ],
					// plugin: [
					// 	[
					// 		"factor-bundle", 
					// 		{ 
					// 			outputs: [
					// 				"./dist/js/main-home.js",
					// 				"./dist/js/main-products.js"
					// 			] 
					// 		}
					// 	]
					// ]
				},
				files: {
					'<%= pkg.build.js.main %>/module.js': '<%= include_files.js.es2015module %>',
				}
			}
		},

		babel: {
			options: {
				sourceMap: false
			},
			dist: {
				files: [
					{
						expand: true,
						cwd: '<%= pkg.src.js.cacheEs6 %>',
						src: ['**/*.es6.js'],
						// dest: '<%= pkg.build.js.main %>',
						dest: '<%= pkg.src.js.cacheEs5 %>',
						ext: '.js'
					},
				]
			},
			// es2015module: {
			// 	files: {
			// 		'<%= pkg.build.js.main %>/module.js': '<%= include_files.js.es2015module %>',
			// 	}

			// }
		},

		concat: {
			dist: {
				files: {
					'<%= pkg.src.js.cacheEs6 %>/common.es6.js': '<%= include_files.js.es2015 %>',
					'<%= pkg.src.js.cacheLibs %>/libs.js': '<%= include_files.js.libs %>',
					// '<%= pkg.buildAdmin.js %>/common.js': '<%= include_files.js_admin %>'
				}
			},
			dist_full: {
				src: [
					'<%= pkg.src.js.cacheLibs %>/libs.js', 
					'<%= pkg.src.js.cacheEs5 %>/common.js', 
				],
				dest: '<%= pkg.build.js.main %>/common.js',
			}
		},

		uglify: {
			my_target: {
				options: {
					mangle: false
				},
				files: {
					'<%= pkg.build.js.main %>/common.min.js': '<%= pkg.build.js.main %>/common.js',
					// '<%= pkg.buildAdmin.js %>/common.min.js': '<%= pkg.buildAdmin.js %>/common.js'
				}
			}
		},

		watch: {
			grunt: { 
				files: [
					'Gruntfile.js'
				] 
			},
			sass: {
				files: ['scss/**/*.scss'],
				tasks: ['scss'],
				// options: {
				// 	livereload: true,
				// 	// livereload: {
				// 	// 	port: 35729
				// 	// }
				// }
			},
			js: {
				files: [
					'js/**/*.js',
				],
				tasks: ['js', 'js2'],
				options: {
					// livereload: true,
					livereload: 35730,
				}
			},
			// livereload: {
			// 	options: { 
			// 		livereload: true 
			// 	},
			// 	files: ['<%= pkg.build.css %>/*.css'],
			// },
		},

	});


	require("load-grunt-tasks")(grunt);

	grunt.registerTask('js', ['concat:dist', 'babel:dist', 'concat:dist_full', 'uglify']);
	grunt.registerTask('js2', ['browserify']);

	grunt.registerTask('svg', ['clean', 'svgmin', 'svgcss']);
	grunt.registerTask('scss', ['sass', 'postcss'/*, 'cssmin'*/]);
};