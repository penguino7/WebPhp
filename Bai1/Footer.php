<?php
if (!isset($relBase)) {
    $relBase = '../';
    $currentScriptFile = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $rootProjectDir = str_replace('\\', '/', dirname(__DIR__));
    if (!empty($currentScriptFile) && strpos($currentScriptFile, $rootProjectDir) === 0) {
        $subPath = trim(substr($currentScriptFile, strlen($rootProjectDir)), '/');
        $depth = $subPath ? count(explode('/', $subPath)) : 0;
        $relBase = str_repeat('../', $depth);
    }
}
?>
        </div> <!-- Đóng container -->
        <footer>
            <div class="footer-banner">
                <img src="<?= $relBase ?>src/image/footer.jpg" alt="Footer Banner">
            </div>
        </footer>
        </div> <!-- Đóng wrapper -->
        <script src="<?= $relBase ?>src/js/script.js?v=<?php echo time(); ?>"></script>
        </body>

        </html>