<?php
class Absensiswa extends Database
{

	public function data_Absen($userid)
	{
		try {
			$sqldef = "SELECT * FROM absen WHERE nik = :userid";
			$sql = "SELECT absen.id_absen, absen.nik, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan 
                FROM absen 
                JOIN status_absen ON absen.id_status = status_absen.id_status 
                WHERE absen.nik = :userid
                ORDER BY absen.id_absen DESC";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(":userid", $userid);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function cek_Absenmasuk($userid)
	{
		try {
			$sql = "SELECT * FROM absen WHERE tanggal_absen = CURRENT_DATE() AND nik = :userid";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(':userid', $userid);
			if ($stmt->execute()) {
				if ($stmt->rowCount() == 1) {
					return true;
				} else {
					return false;
				}
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function cek_Absenkeluar($userid)
	{
		try {
			$sql = "SELECT * FROM absen WHERE tgl_keluar = CURRENT_DATE() AND nik = :userid";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(':userid', $userid);
			$stmt->execute();
			if ($stmt->rowCount() == 1) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function get_idabsen($userid)
	{
		try {
			$sql = "SELECT id_absen FROM absen WHERE tanggal_absen = CURRENT_DATE() AND nik = :userid";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(":userid", $userid);
			$stmt->execute();
			$stmt->bindColumn(1, $this->id_absen);
			$stmt->fetch(PDO::FETCH_BOUND);
			return $this->id_absen ? $this->id_absen : '';
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function insert_Absenmasuk($userid, $id_status, $id_jadwal, $tanggal_absen, $jam_masuk, $keterangan, $file_foto, $latlong)
	{
		try {
			$sql = "INSERT INTO absen(nik, id_status, id_jadwal, tanggal_absen, jam_masuk, keterangan, foto_absen, latlong) VALUES(:nik,:id_status, :id_jadwal, :tanggal_absen, :jam_masuk, :keterangan, :foto_absen, :latlong)";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(":nik", $userid);
			$stmt->bindParam(":id_status", $id_status);
			$stmt->bindParam(":id_jadwal", $id_jadwal);
			$stmt->bindParam(":tanggal_absen", $tanggal_absen);
			$stmt->bindParam(":jam_masuk", $jam_masuk);
			$stmt->bindParam(":keterangan", $keterangan);
			$stmt->bindParam(":foto_absen", $file_foto);
			$stmt->bindParam(":latlong", $latlong);

			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function update_Absenkeluar($tgl_keluar, $jam_keluar, $id_absen)
	{
		try {
			$sql = "UPDATE absen SET tgl_keluar=:tgl_keluar, jam_keluar=:jam_keluar WHERE id_absen=:id_absen";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(":tgl_keluar", $tgl_keluar);
			$stmt->bindParam(":jam_keluar", $jam_keluar);
			$stmt->bindParam(":id_absen", $id_absen);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function update_Logbook($userid, $logbook_input)
	{
		try {
			$sql = "UPDATE absen SET logbook = :logbook WHERE nik = :userid AND jam_keluar IS NULL";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(':logbook', $logbook_input);
			$stmt->bindParam(':userid', $userid);
			$result = $stmt->execute();

			if ($result) {
				return true;
			} else {
				// Log or print the error message for debugging
				error_log("Failed to update logbook for user: $userid");
				return false;
			}
		} catch (PDOException $e) {
			// Log the exception message
			error_log("PDOException: " . $e->getMessage());
			return false;
		}
	}

	public function cek_Logbook($userid)
	{
		try {
			$sql = "SELECT logbook FROM absen WHERE nik = :userid AND jam_keluar IS NULL";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(':userid', $userid);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && !empty($result['logbook'])) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			// Log the exception message
			error_log("PDOException: " . $e->getMessage());
			return false;
		}
	}

	public function __destruct()
	{
		return true;
	}
}
?>