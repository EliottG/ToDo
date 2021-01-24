Contribution Guide
============

## How to contribute to the project:

First you'll need to follow the [README](./README.md) to install the project
Then if you want to add a new Feature on ToDoList, you must create your own branch
``` bash
git branch feature-branch
git checkout -b feature-branch
```

When you have finished, push your feature branch to open a Pull Request

    git push origin feature-branch

Your pull request will be analysed and will be accepted according to the opinion of the lead developer.


## Add features

It is always appreciated to offer new features. However, take some time to think it over, make sure that this functionality matches the objectives of the project.

It's up to you to present solid arguments to convince the project developers of the benefits of this feature.

Don't forget to follow the [convention](#the-convention-to-follow) to increase your features lucky to be accepted

## Issues

A bug is a design flaw in a computer program that causes it to malfunction.
If you have located an error in the code, you must before check if nobody has already reported the issue.
In your issue ticket, you can add screenshots of the place where the issue has been find.

## The convention to follow

  - The feature that you want to add must be tested in the folder "tests"

  - Your code must respect some PSR's  (PSR-1,PSR-4, PSR-12..)

  - Your commit message must be clear and understable, so that it will be easier to understand your updates

  - When your Pull Request can close an Issue, you must add in your title **closes #numberOfTheTicket** to close
the issue

## Prettier

To keep a good indentation of the code, we ask for the installation of the Prettier plugin which will allow the code to be reformatted at each save.
