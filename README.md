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
Gdy kontener jest uruchomiony
```bash
docker compose exec symfony composer test
```

### Odpalanie coverage
Gdy kontener jest uruchomiony
```bash
docker compose exec symfony composer coverage
```
Wynik dostępny w podfolderze projektu: `symfony/var/coverage/index.html`

## API
Dostępne pod adresem `http://localhost:8000/api`

## Dokumentacja API
Dostępna pod adresem `http://localhost:8000/api/doc`

## Podnoszenie i zatrzymywanie kontenerów
Podnoszenie
```bash
docker compose up -d
```

Zatrzymywanie
```bash
docker compose down
```