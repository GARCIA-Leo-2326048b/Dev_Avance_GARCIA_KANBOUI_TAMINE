# Dev_Avance_GARCIA_KANBOUI_TAMINE

## Guide d'installation
1. composer install
2. activez l'extension "extension=fileinfo" dans votre php.ini
3. Créez un fihcier .env:
    Rajoutez y les champs :
    - CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
    - MISTRAL_API_KEY=votre_cle_api
    - DEFAULT_URI=http://localhost
    - DATABASE_URL=votre_url_BD