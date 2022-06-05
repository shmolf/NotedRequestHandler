# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/shmolf/noted-hydrator).

## Set up
```bash
yarn install && composer install
```

## Pull Requests

- **[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - Check the code style with ``$ composer lint`` and fix it with ``$ composer fix-style``.

- **Add tests!** - Your patch won't be accepted if it doesn't have tests.

- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.

- **Create feature branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.


## Running Tests

``` bash
$ composer test
```

## Setup Repo for 'Semantic Release' library

[_Copied from another project_](https://github.com/shmolf/noted-storage-symfony/blob/main/docs/App-Setup.md#setup-ci-for-semantic-release-support)

Because we're committing to the repo as part of semantic release, you need to setup a Personal Access token.
1. [Github URL](https://github.com/settings/tokens/new)
1. Select `write:packages`
   - This will auto-select `read:packages` and `repo:*`
1. Provide a name for the token at the top, and click 'Generate Token' at the bottom
   - I opted for a name like `Noted-Hyrator-CI`, so I could discern where and how it was being used
1. Make sure you copy the token string. You'll need that in the repo's CI Variables settings.
1. Go to your Repo's 'New Secret' page: `https://github.com/<your username>/<your repo>/settings/secrets/actions/new`
1. According to the
   [Documentation](https://github.com/semantic-release/semantic-release/blob/master/docs/usage/ci-configuration.md#push-access-to-the-remote-repository),
   the name of the Github Token should explicitly be `GH_TOKEN`.
   - I'm pretty sure I tried `GITHUB_TOKEN` in the past, and it didn't work, but I may have messed up, as I was still learning.


**Happy coding**!
