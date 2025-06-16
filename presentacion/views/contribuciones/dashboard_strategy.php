<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">💰 Dashboard Financiero - Strategy Pattern</h2>
            <div class="button-group">
                <a href="/contribuciones" class="btn btn-secondary">📋 Lista Normal</a>
                <a href="/contribuciones/crear" class="btn btn-primary">➕ Nueva Contribución</a>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-card">
                <div class="hero-icon">📊</div>
                <h2>Análisis Financiero Inteligente</h2>
                <p>Descubre insights profundos de tus contribuciones con diferentes estrategias de análisis</p>
            </div>
        </div>

        <!-- Strategy Selector -->
        <div class="strategy-selector">
            <h3>🎯 Selecciona tu Estrategia de Análisis</h3>
            <div class="strategy-buttons">
                <a href="/contribuciones/strategy-mensual" class="strategy-btn mensual <?php echo (isset($estrategiaActual) && $estrategiaActual == 'mensual') ? 'active' : ''; ?>">
                    <div class="btn-icon">📅</div>
                    <div class="btn-content">
                        <h4>Análisis Mensual</h4>
                        <p>Rendimiento del mes actual</p>
                    </div>
                </a>
                <a href="/contribuciones/strategy-anual" class="strategy-btn anual <?php echo (isset($estrategiaActual) && $estrategiaActual == 'anual') ? 'active' : ''; ?>">
                    <div class="btn-icon">📊</div>
                    <div class="btn-content">
                        <h4>Análisis Anual</h4>
                        <p>Tendencias del año completo</p>
                    </div>
                </a>
                <a href="/contribuciones/strategy-tipo" class="strategy-btn tipo <?php echo (isset($estrategiaActual) && $estrategiaActual == 'tipo') ? 'active' : ''; ?>">
                    <div class="btn-icon">🏷️</div>
                    <div class="btn-content">
                        <h4>Por Tipo</h4>
                        <p>Distribución por categorías</p>
                    </div>
                </a>
                <a href="/contribuciones/strategy-personalizado" class="strategy-btn personalizado <?php echo (isset($estrategiaActual) && $estrategiaActual == 'personalizado') ? 'active' : ''; ?>">
    <div class="btn-icon">📅</div>
    <div class="btn-content">
        <h4>Análisis Personalizado</h4>
        <p>Elige tu período específico</p>
    </div>
</a>
            </div>
        </div>

        <!-- Results Section -->
        <?php if (isset($resultadoStrategy) && !empty($resultadoStrategy)): ?>
            <div class="results-container">
                <!-- Header con título dinámico -->
                <div class="results-header">
                    <div class="header-info">
                        <h2>
                            <?php
                            $icons = ['mensual' => '📅', 'anual' => '📊', 'por_tipo' => '🏷️'];
                            $titles = [
                                'mensual' => 'Análisis Mensual',
                                'anual' => 'Análisis Anual', 
                                'por_tipo' => 'Análisis por Tipo'
                            ];
                            $tipo = $resultadoStrategy['tipo_calculo'];
                            echo ($icons[$tipo] ?? '📈') . ' ' . ($titles[$tipo] ?? 'Análisis');
                            ?>
                        </h2>
                        <p class="subtitle">
                            <?php
                            if ($tipo == 'mensual' && isset($resultadoStrategy['periodo']['nombre_mes'])) {
                                echo $resultadoStrategy['periodo']['nombre_mes'] . ' ' . $resultadoStrategy['periodo']['año'];
                            } elseif ($tipo == 'anual' && isset($resultadoStrategy['periodo']['año'])) {
                                echo 'Año ' . $resultadoStrategy['periodo']['año'];
                            } elseif ($tipo == 'por_tipo') {
                                echo $resultadoStrategy['periodo']['descripcion'] ?? 'Análisis completo';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="header-badge">
                        <span class="updated-time">
                            Actualizado: <?php echo date('H:i', strtotime($resultadoStrategy['calculado_en'] ?? 'now')); ?>
                        </span>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="kpi-grid">
                    <div class="kpi-card primary">
                        <div class="kpi-icon">💰</div>
                        <div class="kpi-content">
                            <div class="kpi-label">Total General</div>
                            <div class="kpi-value">
                                $<?php echo number_format($resultadoStrategy['totales']['total_general'] ?? 0, 2); ?>
                            </div>
                        </div>
                    </div>

                    <div class="kpi-card success">
                        <div class="kpi-icon">📊</div>
                        <div class="kpi-content">
                            <div class="kpi-label">Contribuciones</div>
                            <div class="kpi-value">
                                <?php 
                                echo $resultadoStrategy['totales']['cantidad_contribuciones'] ?? 
                                     $resultadoStrategy['totales']['tipos_con_contribuciones'] ?? 0;
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($resultadoStrategy['totales']['promedio_diario'])): ?>
                        <div class="kpi-card info">
                            <div class="kpi-icon">📅</div>
                            <div class="kpi-content">
                                <div class="kpi-label">Promedio Diario</div>
                                <div class="kpi-value">$<?php echo number_format($resultadoStrategy['totales']['promedio_diario'], 2); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($resultadoStrategy['totales']['promedio_mensual'])): ?>
                        <div class="kpi-card warning">
                            <div class="kpi-icon">📆</div>
                            <div class="kpi-content">
                                <div class="kpi-label">Promedio Mensual</div>
                                <div class="kpi-value">$<?php echo number_format($resultadoStrategy['totales']['promedio_mensual'], 2); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Period Info Card -->
                <?php if (isset($resultadoStrategy['periodo'])): ?>
                    <div class="period-card">
                        <h4>📅 Información del Período</h4>
                        <div class="period-details">
                            <?php if ($resultadoStrategy['tipo_calculo'] == 'mensual'): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Mes:</span>
                                    <span class="detail-value"><?php echo $resultadoStrategy['periodo']['nombre_mes']; ?> <?php echo $resultadoStrategy['periodo']['año']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Días:</span>
                                    <span class="detail-value"><?php echo $resultadoStrategy['periodo']['dias_en_mes']; ?> días</span>
                                </div>
                            <?php elseif ($resultadoStrategy['tipo_calculo'] == 'anual'): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Año:</span>
                                    <span class="detail-value"><?php echo $resultadoStrategy['periodo']['año']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Meses transcurridos:</span>
                                    <span class="detail-value"><?php echo $resultadoStrategy['periodo']['meses_transcurridos']; ?> meses</span>
                                </div>
                            <?php else: ?>
                                <div class="detail-item">
                                    <span class="detail-label">Período:</span>
                                    <span class="detail-value"><?php echo $resultadoStrategy['periodo']['descripcion']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Tipos analizados:</span>
                                    <span class="detail-value"><?php echo $resultadoStrategy['totales']['tipos_con_contribuciones'] ?? 0; ?> tipos</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Breakdown by Type -->
                <?php if (isset($resultadoStrategy['desglose_por_tipo']) && !empty($resultadoStrategy['desglose_por_tipo'])): ?>
                    <div class="breakdown-section">
                        <h4>🏷️ Distribución por Tipo de Contribución</h4>
                        <div class="breakdown-grid">
                            <?php foreach ($resultadoStrategy['desglose_por_tipo'] as $index => $tipo): ?>
                                <div class="breakdown-card">
                                    <div class="breakdown-header">
                                        <div class="breakdown-icon" style="background: <?php echo ['#3498db', '#e74c3c', '#f39c12', '#27ae60', '#9b59b6'][$index % 5]; ?>">
                                            <?php echo ['💎', '🎁', '❤️', '🌟', '✨'][$index % 5]; ?>
                                        </div>
                                        <h5><?php echo ucfirst($tipo['tipo']); ?></h5>
                                    </div>
                                    <div class="breakdown-amount">$<?php echo number_format($tipo['total'], 2); ?></div>
                                    <div class="breakdown-percentage">
                                        <?php 
                                        $totalGeneral = $resultadoStrategy['totales']['total_general'] ?? 0;
                                        $porcentaje = $totalGeneral > 0 ? ($tipo['total'] / $totalGeneral) * 100 : 0;
                                        echo round($porcentaje, 1); ?>%
                                    </div>
                                    <div class="breakdown-bar">
                                        <div class="breakdown-fill" style="width: <?php echo $porcentaje; ?>%; background: <?php echo ['#3498db', '#e74c3c', '#f39c12', '#27ae60', '#9b59b6'][$index % 5]; ?>"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h4>⚡ Acciones Rápidas</h4>
                    <div class="action-buttons">
                        <a href="/contribuciones/crear" class="action-btn primary">
                            <span class="action-icon">➕</span>
                            Nueva Contribución
                        </a>
                        <a href="/contribuciones/buscar" class="action-btn secondary">
                            <span class="action-icon">🔍</span>
                            Buscar Contribuciones
                        </a>
                        <a href="#" onclick="window.print()" class="action-btn info">
                            <span class="action-icon">🖨️</span>
                            Imprimir Reporte
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Welcome Screen -->
            <div class="welcome-screen">
                <div class="welcome-content">
                    <div class="welcome-icon">🎯</div>
                    <h3>¡Comienza tu Análisis Financiero!</h3>
                    <p>Selecciona una estrategia de análisis arriba para obtener insights detallados de tus contribuciones.</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">📅</div>
                            <h4>Análisis Mensual</h4>
                            <p>Promedios diarios, totales mensuales y tendencias del período actual.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">📊</div>
                            <h4>Análisis Anual</h4>
                            <p>Visión completa del año con comparaciones y proyecciones.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">🏷️</div>
                            <h4>Por Tipo</h4>
                            <p>Distribución detallada por categorías de contribuciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<style>
/* Hero Section */
.hero-section {
    margin-bottom: 40px;
}

.hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.1);
    position: relative;
    overflow: hidden;
}

.hero-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

.hero-icon {
    font-size: 4em;
    margin-bottom: 20px;
    display: block;
}

.hero-card h2 {
    margin: 0 0 15px 0;
    font-size: 2.2em;
    font-weight: 300;
}

.hero-card p {
    margin: 0;
    font-size: 1.2em;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

/* Strategy Selector */
.strategy-selector {
    margin-bottom: 40px;
}

.strategy-selector h3 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 30px;
    font-size: 1.5em;
}

.strategy-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    max-width: 1000px;
    margin: 0 auto;
}

.strategy-btn {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 25px;
    text-decoration: none;
    color: #2c3e50;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.strategy-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.strategy-btn:hover::before {
    left: 100%;
}

.strategy-btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.strategy-btn.mensual:hover, .strategy-btn.mensual.active {
    border-color: #3498db;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.strategy-btn.anual:hover, .strategy-btn.anual.active {
    border-color: #e74c3c;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.strategy-btn.tipo:hover, .strategy-btn.tipo.active {
    border-color: #f39c12;
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: white;
}

.btn-icon {
    font-size: 3em;
    min-width: 60px;
}

.btn-content h4 {
    margin: 0 0 8px 0;
    font-size: 1.3em;
}

.btn-content p {
    margin: 0;
    opacity: 0.8;
    font-size: 0.95em;
}

/* Results Container */
.results-container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border: 1px solid #f1f1f1;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f8f9fa;
}

.header-info h2 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 2em;
}

.subtitle {
    margin: 0;
    color: #7f8c8d;
    font-size: 1.1em;
}

.header-badge {
    background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
    padding: 10px 20px;
    border-radius: 25px;
    color: #2c3e50;
    font-size: 0.9em;
    font-weight: 500;
}

/* KPI Grid */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.kpi-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 15px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-left: 5px solid;
    transition: transform 0.3s ease;
}

.kpi-card:hover {
    transform: translateY(-3px);
}

.kpi-card.primary { border-left-color: #3498db; }
.kpi-card.success { border-left-color: #27ae60; }
.kpi-card.info { border-left-color: #8e44ad; }
.kpi-card.warning { border-left-color: #f39c12; }

.kpi-icon {
    font-size: 2.5em;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(52, 152, 219, 0.1);
}

.kpi-label {
    font-size: 0.9em;
    color: #7f8c8d;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-value {
    font-size: 1.8em;
    font-weight: bold;
    color: #2c3e50;
}

/* Period Card */
.period-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    border-left: 4px solid #3498db;
}

.period-card h4 {
    margin: 0 0 20px 0;
    color: #2c3e50;
}

.period-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.detail-label {
    font-weight: 500;
    color: #7f8c8d;
}

.detail-value {
    font-weight: bold;
    color: #2c3e50;
}

/* Breakdown Section */
.breakdown-section {
    margin-bottom: 40px;
}

.breakdown-section h4 {
    margin-bottom: 25px;
    color: #2c3e50;
    text-align: center;
}

.breakdown-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
}

.breakdown-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #f1f1f1;
    transition: transform 0.3s ease;
}

.breakdown-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.breakdown-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-bottom: 20px;
}

.breakdown-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5em;
    color: white;
}

.breakdown-card h5 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.1em;
}

.breakdown-amount {
    font-size: 1.8em;
    font-weight: bold;
    color: #27ae60;
    margin-bottom: 10px;
}

.breakdown-percentage {
    font-size: 1.2em;
    font-weight: bold;
    color: #3498db;
    margin-bottom: 15px;
}

.breakdown-bar {
    height: 6px;
    background: #ecf0f1;
    border-radius: 3px;
    overflow: hidden;
}

.breakdown-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 1s ease;
}

/* Quick Actions */
.quick-actions {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #f8f9fa;
}

.quick-actions h4 {
    margin-bottom: 20px;
    color: #2c3e50;
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid;
}

.action-btn.primary {
    background: #3498db;
    border-color: #3498db;
    color: white;
}

.action-btn.secondary {
    background: #95a5a6;
    border-color: #95a5a6;
    color: white;
}

.action-btn.info {
    background: #8e44ad;
    border-color: #8e44ad;
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Welcome Screen */
.welcome-screen {
    text-align: center;
    padding: 60px 20px;
}

.welcome-content {
    max-width: 800px;
    margin: 0 auto;
}

.welcome-icon {
    font-size: 5em;
    margin-bottom: 30px;
    display: block;
}

.welcome-content h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 2em;
}

.welcome-content > p {
    color: #7f8c8d;
    font-size: 1.2em;
    margin-bottom: 50px;
    line-height: 1.6;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.feature-card {
    background: white;
    border-radius: 15px;
    padding: 30px 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #f1f1f1;
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.feature-icon {
    font-size: 3em;
    margin-bottom: 20px;
}

.feature-card h4 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.feature-card p {
    color: #7f8c8d;
    line-height: 1.5;
    margin: 0;
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Responsive */
@media (max-width: 768px) {
    .strategy-buttons {
        grid-template-columns: 1fr;
    }
    
    .kpi-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
    
    .breakdown-grid {
        grid-template-columns: 1fr;
    }
    
    .results-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-card {
        padding: 30px 20px;
    }
    
    .hero-card h2 {
        font-size: 1.8em;
    }
    
    .results-container {
        padding: 25px;
    }
}

@media (max-width: 480px) {
    .strategy-btn {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .kpi-card {
        flex-direction: column;
        text-align: center;
    }
    
    .period-details {
        grid-template-columns: 1fr;
    }
    
    .detail-item {
        flex-direction: column;
        gap: 5px;
        text-align: center;
    }
}
</style>