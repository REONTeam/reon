const fs = require("fs");
const mysql = require("mysql2/promise");

const config = JSON.parse(fs.readFileSync("../../config.json"));
const mysqlConfig = {
	host: config["mysql_host"],
	user: config["mysql_user"],
	password: config["mysql_password"],
	database: config["mysql_database"]
}
const regions = ["j", "e"];
const numRoomsPerLevel = 20;

async function updateContent() {
	const connection = await mysql.createConnection(mysqlConfig);
	await connection.beginTransaction();
	for (const region of regions) {
		console.log("Begin battle content update for region " + region);
		await updateContentForRegion(region, connection);
		console.log("Finished battle content update for region " + region);
	}
	await connection.commit();
	//await connection.rollback();
	await connection.end();
}

async function updateContentForRegion(region, connection) {
	for (let i = 0; i < 10 ; i++) {
		for (let j = 0; j < numRoomsPerLevel ; j++) {
			// the first 6 trainers (the last six encountered) are selected by performance
			let [selectedTrainers] = await connection.execute("select id, name, class, pokemon1, pokemon2, pokemon3, message_start, message_win, message_lose from bxt" + region + "_battle_tower_records where level = ? and room = ? order by num_trainers_defeated desc, num_turns_required desc, damage_taken desc, num_fainted_pokemon desc limit 6", [i, j]);
			// the 7th trainer (the first encountered) is selected at random
			let seventhTrainer;
			if (selectedTrainers.length == 6) {
				[seventhTrainer] = await connection.query("select id, name, class, pokemon1, pokemon2, pokemon3, message_start, message_win, message_lose from bxt" + region + "_battle_tower_records where level = ? and room = ? and id not in (?) order by rand() limit 1", [i, j, selectedTrainers.map(trainer => trainer.id)]);
			}
			if (seventhTrainer && seventhTrainer.length != 0) selectedTrainers.push(seventhTrainer[0]);
			
			// delete all records
			await connection.execute("delete from bxt" + region + "_battle_tower_records where level = ? and room = ?", [i, j]);
			
			// delete all previous trainers
			await connection.execute("delete from bxt" + region + "_battle_tower_trainers where level = ? and room = ?", [i, j]);
			
			// insert new trainers
			for (const key in selectedTrainers) {
				await connection.execute("insert into bxt" + region + "_battle_tower_trainers (level, room, no, name, class, pokemon1, pokemon2, pokemon3, message_start, message_win, message_lose) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [i, j, key, selectedTrainers[key].name, selectedTrainers[key].class, selectedTrainers[key].pokemon1, selectedTrainers[key].pokemon2, selectedTrainers[key].pokemon3, selectedTrainers[key].message_start, selectedTrainers[key].message_win, selectedTrainers[key].message_lose]);
			}
			
			// put leader's name onto the honor roll
			if (selectedTrainers.length > 0) await connection.execute("insert into bxt" + region + "_battle_tower_leaders (level, room, name) values (?, ?, ?)", [i, j, selectedTrainers[0].name]);
		}
	}
}

updateContent();