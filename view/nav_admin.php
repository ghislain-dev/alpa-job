<div class="col-md-3 col-lg-2 sidebar d-flex flex-column p-0">
            <div class="px-3 mb-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-person-fill fs-3 me-2"></i>
                <span class="fs-4 fw-bold">Admin Alpa Job</span>
            </div>
            </div>
            <nav class="flex-grow-1">
            <a href="index_admin.php" class="d-flex align-items-center<?php if(basename($_SERVER['PHP_SELF']) == 'dashboard.php') echo ' bg-secondary'; ?>">
                <i class="bi bi-house-door me-2"></i> Tableau de bord
            </a>
            <a href="mes_reservations.php" class="d-flex align-items-center<?php if(basename($_SERVER['PHP_SELF']) == 'reservation.php') echo ' bg-secondary'; ?>">
                <i class="bi bi-calendar-check me-2"></i> Réservations
            </a>
            <a href="produit.php" class="d-flex align-items-center<?php if(basename($_SERVER['PHP_SELF']) == 'produit.php') echo ' bg-secondary'; ?>">
                <i class="bi bi-box me-2"></i> Produits
            </a>
            <a href="categorie.php" class="d-flex align-items-center<?php if(basename($_SERVER['PHP_SELF']) == 'categorie.php') echo ' bg-secondary'; ?>">
                <i class="bi bi-box me-2"></i> categories
            </a>
              <a href="prix.php" class="d-flex align-items-center<?php if(basename($_SERVER['PHP_SELF']) == 'prix.php') echo ' bg-secondary'; ?>">
                <i class="bi bi-box me-2"></i> Fix le prix
            </a>
            <a href="reapro.php" class="d-flex align-items-center<?php if(basename($_SERVER['PHP_SELF']) == 'reapro.php') echo ' bg-secondary'; ?>">
                <i class="bi bi-box me-2"></i> reapprovisionnement
            </a>
            
            <a href="stock.php" class="d-flex align-items-center">
                <i class="bi bi-layers me-2"></i> Mouvements de stock
            </a>
            <a href="commande_admin.php" class="d-flex align-items-center<?php if(basename($_SERVER['PHP_SELF']) == 'commande_admin.php') echo ' bg-secondary'; ?>">
                <i class="bi bi-receipt me-2"></i> Commandes clients
            </a>

            <a href="user.php" class="d-flex align-items-center">
                <i class="bi bi-people me-2"></i> Utilisateurs
            </a>
            
            <a href="deconnexion.php" class="d-flex align-items-center mt-2">
                <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
            </a>
            </nav>
        </div>