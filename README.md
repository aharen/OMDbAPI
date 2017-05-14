# OMDbAPI
PHP class to communicate with OMDbAPI.com API by Brian Fritz

## How to Use

Include the file 

```
composer require aharen/omdbapi

```

Or update your composer.json file accordingly

```
require {
    "aharen/omdbapi" : "2.0.*"
}

```

### Initiating

Since the API will be going private ([read about it](https://www.patreon.com/posts/api-is-going-10743518)) & would be requiring the API key, a small change has been made to how the API will be initiated and how the Poster API will be used.

```php
use aharen\OMDbAPI();

$omdb = new OMDbAPI($api_key, $image_host, $assoc);

```

The first parameter will be the API Key, the secound is the host to use (false = omdbapi, true = poster api, false by default) & third is assoc (true = assoc on, false = assoc off, false by default)

### Search OMDb API

Requires search keyword and accepts type (movie, series or episode) & year


```php
use aharen\OMDbAPI();

$omdb = new OMDbAPI();

$omdb->search($keyword, $type, $year);

```

Example usage


```php
$omdb->search('spider');

```

Output

```php
stdClass Object
(
    [code] => 200
    [message] => OK
    [data] => stdClass Object
        (
            [0] => stdClass Object
                (
                    [Title] => Spider-Man
                    [Year] => 2002
                    [imdbID] => tt0145487
                    [Type] => movie
                )

            [1] => stdClass Object
                (
                    [Title] => The Amazing Spider-Man
                    [Year] => 2012
                    [imdbID] => tt0948470
                    [Type] => movie
                )

            [2] => stdClass Object
                (
                    [Title] => Spider-Man 2
                    [Year] => 2004
                    [imdbID] => tt0316654
                    [Type] => movie
                )

            [3] => stdClass Object
                (
                    [Title] => Spider-Man 3
                    [Year] => 2007
                    [imdbID] => tt0413300
                    [Type] => movie
                )

            [4] => stdClass Object
                (
                    [Title] => The Amazing Spider-Man 2
                    [Year] => 2014
                    [imdbID] => tt1872181
                    [Type] => movie
                )

            [5] => stdClass Object
                (
                    [Title] => Along Came a Spider
                    [Year] => 2001
                    [imdbID] => tt0164334
                    [Type] => movie
                )

            [6] => stdClass Object
                (
                    [Title] => Spider
                    [Year] => 2002
                    [imdbID] => tt0278731
                    [Type] => movie
                )

            [7] => stdClass Object
                (
                    [Title] => Spider-Man
                    [Year] => 1994–1998
                    [imdbID] => tt0112175
                    [Type] => series
                )

            [8] => stdClass Object
                (
                    [Title] => Kiss of the Spider Woman
                    [Year] => 1985
                    [imdbID] => tt0089424
                    [Type] => movie
                )

            [9] => stdClass Object
                (
                    [Title] => The Spectacular Spider-Man
                    [Year] => 2008–2009
                    [imdbID] => tt0976192
                    [Type] => series
                )

        )

)

```

Usage examples

```php
// search for all 'series' that contain 'spider' in the title
$omdb->search('spider', 'series');

// search for all 'series' that contain 'spider' in the title and is from '2014'
$omdb->search('spider', 'series', '2014');

```
### Associative mode

You can also use this library in associative mode, resulting in arrays instead of stdClass
instances, passing the second constructor argument to true:

```
// Associative mode (results will be associative arrays)
$omdb = new OMDbAPI(null, true);
```

### Fetch movie details

Fetch details of a movie, series or episode. details can be fetched by either IMDB ID or the Title

Usage example

```php
// get details for IMDB ID 'tt0338013'
$omdb->fetch('i', 'tt0338013');

// get details for title 'eternal sunshine'
$omdb->fetch('t', 'eternal sunshine');
```

Output for both of the above queires

```php
stdClass Object
(
    [code] => 200
    [message] => OK
    [data] => stdClass Object
        (
            [Title] => Eternal Sunshine of the Spotless Mind
            [Year] => 2004
            [Rated] => R
            [Released] => 19 Mar 2004
            [Runtime] => 108 min
            [Genre] => Drama, Romance, Sci-Fi
            [Director] => Michel Gondry
            [Writer] => Charlie Kaufman (story), Michel Gondry (story), Pierre Bismuth (story), Charlie Kaufman (screenplay)
            [Actors] => Jim Carrey, Kate Winslet, Gerry Robert Byrne, Elijah Wood
            [Plot] => When their relationship turns sour, a couple undergoes a procedure to have each other erased from their memories. But it is only through the process of loss that they discover what they had to begin with.
            [Language] => English
            [Country] => USA
            [Awards] => Won 1 Oscar. Another 64 wins & 62 nominations.
            [Poster] => http://ia.media-imdb.com/images/M/MV5BMTY4NzcwODg3Nl5BMl5BanBnXkFtZTcwNTEwOTMyMw@@._V1_SX300.jpg
            [Metascore] => 89
            [imdbRating] => 8.4
            [imdbVotes] => 533,088
            [imdbID] => tt0338013
            [Type] => movie
            [Response] => True
        )

)
```

### Fetching episodes details

You can also use the `fetch` third parameter to add extra parameters, for instance:

```php
//                 Dexter (TV show)
$omdb->fetch('i', 'tt0773262', ['Season' => 1])
```

This will add the `Season=1` parameter

## Credits

Unit Tests & Associative Mode by [@Gregwar](https://github.com/Gregwar) (Cheers)
