# OMDbAPI
PHP class to communicate with OMDbAPI.com API by Brian Fritz

## How to Use

Include the file 

```php

include_once('OMDbAPI.php');

```

### Search OMDb API

Requires search keyword and accepts type (movie, series or episode) & year


```php

$omdb->search($keyword, $type, $year);

```

Example usage


```php

$omdb = new OMDbAPI();

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

### Fetch movie details

Fetch details of a movie, series or episode. details can be fetched by either IMDB ID or the Title

Usage example

```php

// get details for IMDB ID 'tt0338013'
$omdb->fetch('i', 'tt0338013');

// get details for title 'eternal sunshine'
$omdb->fetch('t', 'eternal sunshine');

```
