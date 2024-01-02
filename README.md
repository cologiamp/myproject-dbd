### DBD Digital / Wealth At Work

# Adviser Hub

## About AdviserHub


## Setup:

- Run the Database seeder *before* running php artisan config:cache fot the first time as the seeder needs the .env 

## Git


## Jira


## Local development of IO package

To do local development, create a Packages directory, with waw/io as a file directory structure, and then create a symlink rather than use the Git version management for that package.

You can do this by setting the Repository in the composer.json file.

    "repositories": [
        {
            "type": "path",
            "url": "packages/waw/io",
            "options": {
                "symlink": true
            }
        }
    ],
