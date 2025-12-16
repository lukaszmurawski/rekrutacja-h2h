# Contact Submission API

## Wymagania

Przed rozpoczęciem upewnij się, że masz zainstalowane:

1.  **Docker & Docker Compose**
2.  **Git**

## Klonowanie Repozytorium

```bash
git clone git@github.com:lukaszmurawski/rekrutacja-h2h.git
cd rekrutacja-h2h
```

## Uruchomienie Projektu
```bash
./init.sh
```

## Odpalenie testów
```bash
docker compose exec symfony composer test
```

### Odpalanie coverage
```bash
docker compose exec symfony composer coverage
```

## API
Dostępne pod adresem `http://localhost:8000/`

## Dokumentacja API
Dostępna pod adresem `http://localhost:8000/api/doc`