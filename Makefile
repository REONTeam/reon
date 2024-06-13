all:
	@docker compose up --detach

clean:
	@docker compose down

account: all
	@docker compose exec -w /var/www/reon/web/scripts web php add_user.php