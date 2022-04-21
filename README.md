# PHP-CSV-to-Array

Simple script which reads a CSV file and converts the data to an array

The returned array can be:
- Associative using the titles
- Associative using titles as separate entries
- Returned plain, formatted as the CSV

Below is a example of how to construct the class:

```
$CSVData = new CSVImport(
 '/var/www/vhosts/jaybeeplant.co.uk/httpdocs/bedrock/web/app/themes/MO-Wordpress/assets/uploadCSV.csv',
 ',',
 ["id", "description", "title", "object_type", "name"],
 false,
 true
);
```

The constructed class will use the following arguments:
- CSV Location *Required
– String
- CSV separator
– String
- CSV included titles (for export)
– Array
- Set as a group
– Boolean
- Include the CSV title
– Boolean

Using ```$CSVData->getData();``` you can access the returned array

As this is very simple; the code isn't fully structured. If i’m planning on using this regularly I shall update it to include:
- PSR standards
- Include read and write (perhaps as a library)
- Make loggin a dependency
- Reading, writing and output to be different dependencies
- Output into different formats (JSON)
- Pass arguments as to constructor as array
- Commenting and adding descriptions
- Plus more
