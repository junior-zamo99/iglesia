<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">📅 Análisis Personalizado - Strategy Pattern</h2>
            <div class="button-group">
                <a href="/contribuciones" class="btn btn-secondary">📋 Lista Normal</a>
                <a href="/contribuciones/crear" class="btn btn-primary">➕ Nueva Contribución</a>
            </div>
        </div>

        <!-- Selector de Fechas -->
        <div class="date-selector-card">
            <h3>🎯 Selecciona tu Período de Análisis</h3>
            <form method="GET" action="/contribuciones/strategy-personalizado" class="date-form">
                <div class="date-inputs">
                    <div class="input-group">
                        <label for="fecha_inicio">📅 Fecha Inicio:</label>
                        <input type="date" 
                               id="fecha_inicio" 
                               name="fecha_inicio" 
                               value="<?php echo $_GET['fecha_inicio'] ?? date('Y-m-01'); ?>"
                               max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="input-group">
                        <label for="fecha_fin">📅 Fecha Fin:</label>
                        <input type="date" 
                               id="fecha_fin" 
                               name="fecha_fin" 
                               value="<?php echo $_GET['fecha_fin'] ?? date('Y-m-d'); ?>"
                               max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <button type="submit" class="analyze-btn">
                        🔍 Analizar Período
                    </button>
                </div>
                
                <!-- Botones de períodos predefinidos -->
                <div class="quick-periods">
                    <h4>⚡ Períodos Rápidos:</h4>
                    <div class="period-buttons">
                        <a href="?fecha_inicio=<?php echo date('Y-m-01'); ?>&fecha_fin=<?php echo date('Y-m-d'); ?>" class="period-btn">📅 Este Mes</a>
                        <a href="?fecha_inicio=<?php echo date('Y-m-01', strtotime('last month')); ?>&fecha_fin=<?php echo date('Y-m-t', strtotime('last month')); ?>" class="period-btn">📆 Mes Pasado</a>
                        <a href="?fecha_inicio=<?php echo date('Y-01-01'); ?>&fecha_fin=<?php echo date('Y-m-d'); ?>" class="period-btn">📊 Este Año</a>
                        <a href="?fecha_inicio=<?php echo date('Y-m-d', strtotime('-7 days')); ?>&fecha_fin=<?php echo date('Y-m-d'); ?>" class="period-btn">📈 Últimos 7 días</a>
                        <a href="?fecha_inicio=<?php echo date('Y-m-d', strtotime('-30 days')); ?>&fecha_fin=<?php echo date('Y-m-d'); ?>" class="period-btn">📉 Últimos 30 días</a>
                        <a href="?fecha_inicio=<?php echo date('Y-m-d', strtotime('-90 days')); ?>&fecha_fin=<?php echo date('Y-m-d'); ?>" class="period-btn">📋 Últimos 90 días</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Navigation a otras estrategias -->
        <div class="strategy-nav">
            <a href="/contribuciones/strategy-mensual" class="nav-strategy mensual">📅 Mensual</a>
            <a href="/contribuciones/strategy-anual" class="nav-strategy anual">📊 Anual</a>
            <a href="/contribuciones/strategy-tipo" class="nav-strategy tipo">🏷️ Por Tipo</a>
            <span class="nav-strategy personalizado active">📅 Personalizado</span>
        </div>

        <!-- Resultados -->
        <?php if (isset($resultadoStrategy) && !empty($resultadoStrategy)): ?>
            <div class="results-container">
                <!-- Header del período -->
                <div class="period-header">
                    <div class="period-info">
                        <h2>📈 <?php echo $resultadoStrategy['periodo']['descripcion']; ?></h2>
                        <div class="period-badges">
                            <span class="badge primary"><?php echo $resultadoStrategy['periodo']['tipo_periodo']; ?></span>
                            <span class="badge secondary"><?php echo $resultadoStrategy['periodo']['dias_en_periodo']; ?> días</span>
                            <?php if (isset($resultadoStrategy['estadisticas_avanzadas']['crecimiento']['tendencia'])): ?>
                                <span class="badge <?php echo $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['tendencia']; ?>">
                                    <?php 
                                    $tendencia = $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['tendencia'];
                                    echo $tendencia === 'crecimiento' ? '📈 Crecimiento' : 
                                         ($tendencia === 'decrecimiento' ? '📉 Decrecimiento' : '➡️ Estable');
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- KPIs Principales -->
                <div class="kpi-grid">
                    <div class="kpi-card primary">
                        <div class="kpi-icon">💰</div>
                        <div class="kpi-content">
                            <div class="kpi-label">Total del Período</div>
                            <div class="kpi-value">$<?php echo number_format($resultadoStrategy['totales']['total_general'], 2); ?></div>
                            <?php if (isset($resultadoStrategy['estadisticas_avanzadas']['crecimiento']['porcentaje_crecimiento'])): ?>
                                <div class="kpi-trend <?php echo $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['porcentaje_crecimiento'] >= 0 ? 'positive' : 'negative'; ?>">
                                    <?php echo $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['porcentaje_crecimiento']; ?>% vs período anterior
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="kpi-card success">
                        <div class="kpi-icon">📊</div>
                        <div class="kpi-content">
                            <div class="kpi-label">Contribuciones</div>
                            <div class="kpi-value"><?php echo $resultadoStrategy['totales']['cantidad_contribuciones']; ?></div>
                            <div class="kpi-subtitle">
                                En <?php echo $resultadoStrategy['estadisticas_avanzadas']['dias_con_contribuciones']; ?> días activos
                            </div>
                        </div>
                    </div>

                    <div class="kpi-card info">
                        <div class="kpi-icon">📅</div>
                        <div class="kpi-content">
                            <div class="kpi-label">Promedio Diario</div>
                            <div class="kpi-value">$<?php echo number_format($resultadoStrategy['totales']['promedio_diario'], 2); ?></div>
                            <div class="kpi-subtitle">
                                Proyección mensual: $<?php echo number_format($resultadoStrategy['estadisticas_avanzadas']['proyeccion_mensual'], 2); ?>
                            </div>
                        </div>
                    </div>

                    <div class="kpi-card warning">
                        <div class="kpi-icon">🎯</div>
                        <div class="kpi-content">
                            <div class="kpi-label">Promedio por Contribución</div>
                            <div class="kpi-value">$<?php echo number_format($resultadoStrategy['totales']['promedio_por_contribucion'], 2); ?></div>
                            <div class="kpi-subtitle">
                                Rango: $<?php echo number_format($resultadoStrategy['totales']['contribucion_mas_baja'], 2); ?> - $<?php echo number_format($resultadoStrategy['totales']['contribucion_mas_alta'], 2); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contribuciones Destacadas -->
                <?php if (isset($resultadoStrategy['contribuciones_destacadas']) && $resultadoStrategy['contribuciones_destacadas']['mas_alta']): ?>
                    <div class="destacadas-section">
                        <h4>🌟 Contribuciones Destacadas</h4>
                        <div class="destacadas-grid">
                            <div class="destacada-card highest">
                                <div class="destacada-icon">🏆</div>
                                <div class="destacada-content">
                                    <h5>Más Alta</h5>
                                    <div class="destacada-amount">$<?php echo number_format($resultadoStrategy['contribuciones_destacadas']['mas_alta']['monto'], 2); ?></div>
                                    <div class="destacada-details">
                                        <span><?php echo $resultadoStrategy['contribuciones_destacadas']['mas_alta']['nombre']; ?> <?php echo $resultadoStrategy['contribuciones_destacadas']['mas_alta']['apellido']; ?></span>
                                        <span><?php echo date('d/m/Y', strtotime($resultadoStrategy['contribuciones_destacadas']['mas_alta']['fecha'])); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="destacada-card lowest">
                                <div class="destacada-icon">📌</div>
                                <div class="destacada-content">
                                    <h5>Más Baja</h5>
                                    <div class="destacada-amount">$<?php echo number_format($resultadoStrategy['contribuciones_destacadas']['mas_baja']['monto'], 2); ?></div>
                                    <div class="destacada-details">
                                        <span><?php echo $resultadoStrategy['contribuciones_destacadas']['mas_baja']['nombre']; ?> <?php echo $resultadoStrategy['contribuciones_destacadas']['mas_baja']['apellido']; ?></span>
                                        <span><?php echo date('d/m/Y', strtotime($resultadoStrategy['contribuciones_destacadas']['mas_baja']['fecha'])); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="destacada-card recent">
                                <div class="destacada-icon">🕐</div>
                                <div class="destacada-content">
                                    <h5>Más Reciente</h5>
                                    <div class="destacada-amount">$<?php echo number_format($resultadoStrategy['contribuciones_destacadas']['mas_reciente']['monto'], 2); ?></div>
                                    <div class="destacada-details">
                                        <span><?php echo $resultadoStrategy['contribuciones_destacadas']['mas_reciente']['nombre']; ?> <?php echo $resultadoStrategy['contribuciones_destacadas']['mas_reciente']['apellido']; ?></span>
                                        <span><?php echo date('d/m/Y', strtotime($resultadoStrategy['contribuciones_destacadas']['mas_reciente']['fecha'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Distribución Semanal -->
                <?php if (isset($resultadoStrategy['estadisticas_avanzadas']['distribucion_semanal'])): ?>
                    <div class="weekly-distribution">
                        <h4>📊 Distribución por Día de la Semana</h4>
                        <div class="weekly-grid">
                            <?php foreach ($resultadoStrategy['estadisticas_avanzadas']['distribucion_semanal'] as $dia => $monto): ?>
                                <div class="day-card">
                                    <div class="day-name"><?php echo $dia; ?></div>
                                    <div class="day-amount">$<?php echo number_format($monto, 2); ?></div>
                                    <div class="day-bar">
                                        <?php 
                                        $maxMonto = max($resultadoStrategy['estadisticas_avanzadas']['distribucion_semanal']);
                                        $percentage = $maxMonto > 0 ? ($monto / $maxMonto) * 100 : 0;
                                        ?>
                                        <div class="day-fill" style="height: <?php echo $percentage; ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Desglose por Tipo -->
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
                                        $totalGeneral = $resultadoStrategy['totales']['total_general'];
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

                <!-- Comparación con Período Anterior -->
                <?php if (isset($resultadoStrategy['estadisticas_avanzadas']['crecimiento']) && $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['total_anterior'] > 0): ?>
                    <div class="comparison-section">
                        <h4>📈 Comparación con Período Anterior</h4>
                        <div class="comparison-card">
                            <div class="comparison-item">
                                <div class="comparison-label">Período Actual</div>
                                <div class="comparison-value current">$<?php echo number_format($resultadoStrategy['totales']['total_general'], 2); ?></div>
                                <div class="comparison-period"><?php echo $resultadoStrategy['periodo']['descripcion']; ?></div>
                            </div>
                            
                            <div class="comparison-arrow">
                                <?php echo $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['diferencia_monto'] >= 0 ? '📈' : '📉'; ?>
                            </div>
                            
                            <div class="comparison-item">
                                <div class="comparison-label">Período Anterior</div>
                                <div class="comparison-value previous">$<?php echo number_format($resultadoStrategy['estadisticas_avanzadas']['crecimiento']['total_anterior'], 2); ?></div>
                                <div class="comparison-period">
                                    <?php 
                                    $fechaInicioAnterior = $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['periodo_comparacion']['fecha_inicio'];
                                    $fechaFinAnterior = $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['periodo_comparacion']['fecha_fin'];
                                    echo date('d/m/Y', strtotime($fechaInicioAnterior)) . ' - ' . date('d/m/Y', strtotime($fechaFinAnterior));
                                    ?>
                                </div>
                            </div>
                            
                            <div class="comparison-result">
                                <div class="result-label">Diferencia</div>
                                <div class="result-amount <?php echo $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['diferencia_monto'] >= 0 ? 'positive' : 'negative'; ?>">
                                    $<?php echo number_format($resultadoStrategy['estadisticas_avanzadas']['crecimiento']['diferencia_monto'], 2); ?>
                                </div>
                                <div class="result-percentage">
                                    (<?php echo $resultadoStrategy['estadisticas_avanzadas']['crecimiento']['porcentaje_crecimiento']; ?>%)
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Welcome Message -->
            <div class="welcome-message">
                <div class="welcome-icon">📅</div>
                <h3>¡Analiza tu Período Personalizado!</h3>
                <p>Selecciona las fechas de inicio y fin arriba para generar un análisis detallado de tus contribuciones en ese período específico.</p>
                
                <div class="benefits-list">
                    <div class="benefit-item">
                        <span class="benefit-icon">📊</span>
                        <span>Estadísticas detalladas del período</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">📈</span>
                        <span>Comparación con período anterior</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">🏷️</span>
                        <span>Desglose por tipos de contribución</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">🌟</span>
                        <span>Contribuciones destacadas</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<style>
/* Date Selector Card */
.date-selector-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.1);
}

.date-selector-card h3 {
    margin: 0 0 25px 0;
    text-align: center;
    font-size: 1.5em;
}

.date-form {
    max-width: 800px;
    margin: 0 auto;
}

.date-inputs {
    display: flex;
    gap: 20px;
    align-items: end;
    margin-bottom: 25px;
    flex-wrap: wrap;
    justify-content: center;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.input-group label {
    font-weight: 500;
    font-size: 0.95em;
}

.input-group input[type="date"] {
    padding: 12px 15px;
    border: none;
    border-radius: 10px;
    font-size: 1em;
    background: rgba(255, 255, 255, 0.9);
    color: #2c3e50;
    min-width: 160px;
}

.analyze-btn {
    background: #27ae60;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-size: 1em;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.analyze-btn:hover {
    background: #229954;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
}

.quick-periods {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 20px;
}

.quick-periods h4 {
    margin: 0 0 15px 0;
    text-align: center;
    font-size: 1.1em;
}

.period-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}

.period-btn {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.9em;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.period-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-1px);
}

/* Strategy Navigation */
.strategy-nav {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.nav-strategy {
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    background: white;
    color: #2c3e50;
}

.nav-strategy.mensual:hover { border-color: #3498db; color: #3498db; }
.nav-strategy.anual:hover { border-color: #e74c3c; color: #e74c3c; }
.nav-strategy.tipo:hover { border-color: #f39c12; color: #f39c12; }

.nav-strategy.active {
    background: #8e44ad;
    border-color: #8e44ad;
    color: white;
}

/* Period Header */
.period-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    text-align: center;
}

.period-header h2 {
    margin: 0 0 15px 0;
    color: #2c3e50;
}

.period-badges {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.badge {
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 500;
}

.badge.primary { background: #3498db; color: white; }
.badge.secondary { background: #95a5a6; color: white; }
.badge.crecimiento { background: #27ae60; color: white; }
.badge.decrecimiento { background: #e74c3c; color: white; }
.badge.estable { background: #f39c12; color: white; }

/* KPI Improvements */
.kpi-trend {
    font-size: 0.8em;
    margin-top: 5px;
    font-weight: 500;
}

.kpi-trend.positive { color: #27ae60; }
.kpi-trend.negative { color: #e74c3c; }

.kpi-subtitle {
    font-size: 0.8em;
    color: #7f8c8d;
    margin-top: 5px;
}

/* Destacadas Section */
.destacadas-section {
    margin-bottom: 40px;
}

.destacadas-section h4 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
}

.destacadas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.destacada-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-left: 4px solid;
    transition: transform 0.3s ease;
}

.destacada-card:hover {
    transform: translateY(-3px);
}

.destacada-card.highest { border-left-color: #f39c12; }
.destacada-card.lowest { border-left-color: #3498db; }
.destacada-card.recent { border-left-color: #27ae60; }

.destacada-card {
    display: flex;
    align-items: center;
    gap: 15px;
}

.destacada-icon {
    font-size: 2.5em;
    min-width: 60px;
}

.destacada-content h5 {
    margin: 0 0 8px 0;
    color: #2c3e50;
}

.destacada-amount {
    font-size: 1.3em;
    font-weight: bold;
    color: #27ae60;
    margin-bottom: 8px;
}

.destacada-details {
    display: flex;
    flex-direction: column;
    font-size: 0.9em;
    color: #7f8c8d;
}

/* Weekly Distribution */
.weekly-distribution {
    margin-bottom: 40px;
}

.weekly-distribution h4 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
}

.weekly-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 15px;
    max-width: 700px;
    margin: 0 auto;
}

.day-card {
    background: white;
    border-radius: 10px;
    padding: 15px 10px;
    text-align: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    border: 1px solid #f1f1f1;
    position: relative;
}

.day-name {
    font-size: 0.8em;
    color: #7f8c8d;
    margin-bottom: 8px;
    font-weight: 500;
}

.day-amount {
    font-size: 1em;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 10px;
}

.day-bar {
    height: 40px;
    background: #ecf0f1;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
}

.day-fill {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, #3498db, #5dade2);
    border-radius: 3px;
    transition: height 1s ease;
}

/* Comparison Section */
.comparison-section {
    margin-bottom: 40px;
}

.comparison-section h4 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
}

.comparison-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.comparison-item {
    text-align: center;
    flex: 1;
    min-width: 150px;
}

.comparison-label {
    font-size: 0.9em;
    color: #7f8c8d;
    margin-bottom: 8px;
}

.comparison-value {
    font-size: 1.5em;
    font-weight: bold;
    margin-bottom: 5px;
}

.comparison-value.current { color: #3498db; }
.comparison-value.previous { color: #95a5a6; }

.comparison-period {
    font-size: 0.8em;
    color: #bdc3c7;
}

.comparison-arrow {
    font-size: 3em;
    min-width: 60px;
    text-align: center;
}

.comparison-result {
    text-align: center;
    flex: 1;
    min-width: 150px;
}

.result-label {
    font-size: 0.9em;
    color: #7f8c8d;
    margin-bottom: 8px;
}

.result-amount {
    font-size: 1.3em;
    font-weight: bold;
    margin-bottom: 5px;
}

.result-amount.positive { color: #27ae60; }
.result-amount.negative { color: #e74c3c; }

.result-percentage {
    font-size: 0.9em;
    font-weight: 500;
}

/* Welcome Message */
.welcome-message {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.welcome-icon {
    font-size: 5em;
    margin-bottom: 20px;
}

.welcome-message h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.8em;
}

.welcome-message > p {
    color: #7f8c8d;
    font-size: 1.1em;
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.benefits-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    max-width: 800px;
    margin: 0 auto;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #3498db;
}

.benefit-icon {
    font-size: 1.5em;
    min-width: 30px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .date-inputs {
        flex-direction: column;
        align-items: stretch;
    }
    
    .period-buttons {
        justify-content: center;
    }
    
    .period-btn {
        flex: 1;
        text-align: center;
        min-width: 120px;
    }
    
    .weekly-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .comparison-card {
        flex-direction: column;
        text-align: center;
    }
    
    .comparison-arrow {
        transform: rotate(90deg);
    }
    
    .destacadas-grid {
        grid-template-columns: 1fr;
    }
    
    .destacada-card {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .date-selector-card {
        padding: 20px;
    }
    
    .kpi-grid {
        grid-template-columns: 1fr;
    }
    
    .strategy-nav {
        flex-direction: column;
        align-items: center;
    }
    
    .nav-strategy {
        width: 200px;
        text-align: center;
    }
    
    .weekly-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>