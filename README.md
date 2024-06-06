# Media Supreme Project

This project is a media management system built with PHP and MySQL. It provides a landing page and several APIs for managing media leads.

## Getting Started

You will receive `setup.sql`, `instructions.txt`, and a Postman collection via email. Follow the instructions in `instructions.txt` to set up the project and database.

## API

The project uses the [IP-API](http://ip-api.com/json/) to get the user's IP address and country. This information is used for ...

## Why IP-API?

I chose to use IP-API over other services like [IPify](https://api.ipify.org/) and [IPAPI](https://ipapi.co/) because our API already provides us with the user's IPV4 address and country. IP-API provides this information in a convenient and reliable manner.

## Testing

You can test the APIs using the provided Postman collection. Import the collection into Postman and run the requests.

## Database Population

If you want to populate the database with test data, navigate to the `/htdocs/media-supreme/src` directory and run `php fill-db.php`.
