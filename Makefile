
run:
	@docker compose up --detach --build
	@echo "Web server available at http://localhost:7400"

clean:
	@docker compose down

account: run
	@docker compose exec web php < 