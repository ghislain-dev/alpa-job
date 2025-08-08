<?php
class Reservation {
    private $con;
    private $id, $description, $date, $date_debut, $date_fin, $id_client, $id_salle;

    public function __construct($con) {
        $this->con = $con;
    }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setDescription($desc) { $this->description = $desc; }
    public function setDate($date) { $this->date = $date; }
    public function setDateDebut($debut) { $this->date_debut = $debut; }
    public function setDateFin($fin) { $this->date_fin = $fin; }
    public function setIdClient($client) { $this->id_client = $client; }
    public function setIdSalle($salle) { $this->id_salle = $salle; }

    public function checkConflit(): bool {
        try {
            $sql = "SELECT COUNT(*) FROM reservation 
                    WHERE id_salle = ? AND id_reservation != ? AND (
                        (date_debut < ? AND date_fin > ?) OR
                        (? < date_fin AND ? > date_debut)
                    )";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([
                $this->id_salle, $this->id, $this->date_fin, $this->date_debut,
                $this->date_debut, $this->date_fin
            ]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function add(): bool {
        try {
            $sql = "INSERT INTO reservation (description, date, date_debut, date_fin, id_client, id_salle)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([
                $this->description, $this->date, $this->date_debut, $this->date_fin,
                $this->id_client, $this->id_salle
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update(): bool {
        try {
            $sql = "UPDATE reservation 
                    SET description = ?, date = ?, date_debut = ?, date_fin = ?, id_salle = ?
                    WHERE id_reservation = ?";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([
                $this->description, $this->date, $this->date_debut, $this->date_fin,
                $this->id_salle, $this->id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(): bool {
        try {
            $sql = "DELETE FROM reservation WHERE id_reservation = ?";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAll(): array {
        try {
            $stmt = $this->con->prepare("SELECT r.*, s.nom_salle 
                                         FROM reservation r 
                                         JOIN salle s ON r.id_salle = s.id_salle 
                                         ORDER BY r.date DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function count_reservations(): int {
        try {
            $stmt = $this->con->query("SELECT COUNT(*) FROM reservation");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function get_all_paginated($limit, $offset): array {
        try {
            $query = "SELECT r.*, c.nom AS nom_client, s.nom_salle 
                      FROM reservation r
                      LEFT JOIN client c ON r.id_client = c.id_client
                      LEFT JOIN salle s ON r.id_salle = s.id_salle
                      ORDER BY r.date_debut DESC
                      LIMIT ? OFFSET ?";
            $stmt = $this->con->prepare($query);
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getReservationsByClientAndStatut($id_client, $statut): array {
        try {
            $sql = "SELECT r.*, s.nom_salle 
                    FROM reservation r 
                    JOIN salle s ON r.id_salle = s.id_salle 
                    WHERE r.id_client = ? AND r.statut = ?
                    ORDER BY r.date_debut DESC";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id_client, $statut]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function count_reservations_by_client_and_statut($id_client, $statut): int {
        try {
            $sql = "SELECT COUNT(*) FROM reservation WHERE id_client = ? AND statut = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id_client, $statut]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function get_reservations_by_client_and_statut_paginated($id_client, $statut, $limit, $offset): array {
        try {
            $sql = "SELECT r.*, s.nom_salle 
                    FROM reservation r
                    JOIN salle s ON r.id_salle = s.id_salle
                    WHERE r.id_client = ? AND r.statut = ?
                    ORDER BY r.date_debut DESC
                    LIMIT ? OFFSET ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $id_client, PDO::PARAM_INT);
            $stmt->bindValue(2, $statut, PDO::PARAM_STR);
            $stmt->bindValue(3, $limit, PDO::PARAM_INT);
            $stmt->bindValue(4, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function payer(): bool {
        try {
            $sql = "UPDATE reservation SET statut = 'payée' WHERE id_reservation = ?";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function enregistrerPaiement($id_reservation, $montant): bool {
        try {
            $sql = "INSERT INTO paiement_reservation (id_reservation, montant) VALUES (?, ?)";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([$id_reservation, $montant]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getReservationDetails($id): ?array {
        try {
            $sql = "SELECT r.*, s.nom_salle, s.prix AS prix_salle 
                    FROM reservation r 
                    JOIN salle s ON r.id_salle = s.id_salle 
                    WHERE r.id_reservation = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateStatut($id_reservation, $statut): bool {
        try {
            $sql = "UPDATE reservation SET statut = ? WHERE id_reservation = ?";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([$statut, $id_reservation]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getReservationById($id_reservation): ?array {
        try {
            $sql = "SELECT r.*, s.nom_salle, s.prix AS prix_salle
                    FROM reservation r
                    JOIN salle s ON r.id_salle = s.id_salle
                    WHERE r.id_reservation = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id_reservation]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getReservationsByStatut(string $statut): array {
        try {
            $sql = "SELECT r.*, s.nom_salle, c.nom AS nom_client, s.prix AS prix_salle
                    FROM reservation r
                    JOIN salle s ON r.id_salle = s.id_salle
                    JOIN client c ON r.id_client = c.id_client
                    WHERE r.statut = ?
                    ORDER BY r.date DESC";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$statut]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getClientById($id_client): ?array {
        try {
            $sql = "SELECT id_utilisateur, nom, prenom, email, telephone 
                    FROM utilisateur 
                    WHERE id_utilisateur = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id_client]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>
