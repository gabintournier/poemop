$(function(){
	$(".navigmap").mapael({
		map : {
			name : "france_departments",
			zoom : false,
			mousewheel : false,
			defaultArea: {
				attrs : {
					stroke : "#f8f6f4",
					fill : "#c9d0cf",
					"stroke-width" : 1
				},
				attrsHover : {
					stroke : "#0f393a",
					fill : "#0f393a",
					"stroke-width" : 2
				}
			}
		},

		plots: {
				   'paris': {
					   latitude: 48.86,
					   longitude: 2.3444,
					   text: {content: "PARIS"}
				   },
				   'lyon': {
					   latitude: 45.758888888889,
					   longitude: 4.8413888888889,
					   text: {content: "LYON"}
				   },
				   'Nice': {
					   latitude: 43.7031,
					   longitude: 7.2661,
					   text: {content: "NICE"}
				   },
				   'Marseille': {
					   latitude: 43.300000,
					   longitude: 5.400000,
					   text: {content: "MARSEILLE"}
				   },
				   'Montpellier': {
					   latitude: 43.611900,
					   longitude: 3.877200,
					   text: {content: "MONTPELLIER"}
				   },
				   'Toulouse': {
					   latitude: 43.600000,
					   longitude: 1.433333,
					   text: {content: "TOULOUSE"}
				   },
				   'BORDEAUX': {
					   latitude: 44.8333,
					   longitude: -0.5667,
					   text: {content: "BORDEAUX"}
				   },
				   'NANTES': {
					   latitude: 47.2173,
					   longitude: -1.5534,
					   text: {content: "NANTES"}
				   },
				   'LILLE': {
					   latitude: 50.633333,
					   longitude: 3.066667,
					   text: {content: "LILLE"}
				   }
		},


		legend : {

			area : {
				mode : "horizontal",
				title :"Couverture de Poemop",
				display : false, /* pour utiliser ou non la légende */
				slices : [
					{
						max :5,
						attrs : {
							fill : "#c9d0cf"
						},
						label :"Non couvert"
					},
					{
						min :5,
						max :10,
						attrs : {
							fill : "#c9d0cf"
						},
						label :"Partiellement couvert"
					},
					{
						min :10,
						max :16,
						attrs : {
							fill : "#c9d0cf"
						},
						label :"Totalement couvert"
					}
				]
			}
		},

		areas: {
			"department-59": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-nord-59-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Nord (59)</span><br />Voir les groupements"}
			},
			"department-75": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-paris-75-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Paris (75)</span><br />Voir les groupements"}
			},
			"department-13": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-bouches-du-rhone-13-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Bouches-du-Rhône (13)</span><br />Voir les groupements"}
			},
			"department-69": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-rhone-69-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Rhône (69)</span><br />Voir les groupements"}
			},
			"department-92": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-hauts-de-seine-92-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Hauts-de-Seine (92)</span><br />Voir les groupements"}
			},
			"department-93": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-seine-saint-denis-93-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Seine-Saint-Denis (93)</span><br />Voir les groupements"}
			},
			"department-62": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-pas-de-calais-62-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Pas-de-Calais (62)</span><br />Voir les groupements"}
			},
			"department-33": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-gironde-33-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Gironde (33)</span><br />Voir les groupements"}
			},
			"department-78": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-yvelines-78-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Yvelines (78)</span><br />Voir les groupements"}
			},
			"department-77": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-seine-et-marne-77-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Seine-et-Marne (77)</span><br />Voir les groupements"}
			},
			"department-94": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-val-de-marne-94-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Val-de-Marne (94)</span><br />Voir les groupements"}
			},
			"department-44": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-loire-atlantique-44-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Loire-Atlantique (44)</span><br />Voir les groupements"}
			},
			"department-76": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-seine-maritime-76-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Seine-Maritime (76)</span><br />Voir les groupements"}
			},
			"department-31": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-haute-garonne-31-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Haute-Garonne (31)</span><br />Voir les groupements"}
			},
			"department-38": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-isere-38-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Isère (38)</span><br />Voir les groupements"}
			},
			"department-91": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-essonne-91-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Essonne (91)</span><br />Voir les groupements"}
			},
			"department-95": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-val-d-oise-95-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Val-d'Oise (95)</span><br />Voir les groupements"}
			},
			"department-67": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-bas-rhin-67-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Bas-Rhin (67)</span><br />Voir les groupements"}
			},
			"department-06": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-alpes-maritimes-06-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Alpes-Maritimes (06)</span><br />Voir les groupements"}
			},
			"department-57": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-moselle-57-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Moselle (57)</span><br />Voir les groupements"}
			},
			"department-34": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-herault-34-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Hérault (34)</span><br />Voir les groupements"}
			},
			"department-83": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-var-83-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Var (83)</span><br />Voir les groupements"}
			},
			"department-35": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-ille-et-vilaine-35-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Ille-et-Vilaine (35)</span><br />Voir les groupements"}
			},
			"department-29": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-finistere-29-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Finistère (29)</span><br />Voir les groupements"}
			},
			"department-60": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-oise-60-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Oise (60)</span><br />Voir les groupements"}
			},
			"department-49": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-maine-et-loire-49-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Maine-et-Loire (49)</span><br />Voir les groupements"}
			},
			"department-42": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-loire-42-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Loire (42)</span><br />Voir les groupements"}
			},
			"department-68": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-haut-rhin-68-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Haut-Rhin (68)</span><br />Voir les groupements"}
			},
			"department-74": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-haute-savoie-74-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Haute-Savoie (74)</span><br />Voir les groupements"}
			},
			"department-54": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-meurthe-et-moselle-54-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Meurthe-et-Moselle (54)</span><br />Voir les groupements"}
			},
			"department-56": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-morbihan-56-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Morbihan (56)</span><br />Voir les groupements"}
			},
			"department-30": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-gard-30-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Gard (30)</span><br />Voir les groupements"}
			},
			"department-14": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-calvados-14-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Calvados (14)</span><br />Voir les groupements"}
			},
			"department-45": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-loiret-45-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Loiret (45)</span><br />Voir les groupements"}
			},
			"department-64": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-pyrénées-atlantiques-64-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Pyrénées-Atlantiques (64)</span><br />Voir les groupements"}
			},
			"department-85": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-vendée-85-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Vendée (85)</span><br />Voir les groupements"}
			},
			"department-63": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-puy-de-dome-63-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Puy-de-Dôme (63)</span><br />Voir les groupements"}
			},
			"department-17": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-charente-maritime-17-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Charente-Maritime (17)</span><br />Voir les groupements"}
			},
			"department-01": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-ain-01-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Ain (01)</span><br />Voir les groupements"}
			},
			"department-22": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-cotes-d-armor-22-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Côtes-d'Armor (22)</span><br />Voir les groupements"}
			},
			"department-37": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-indre-et-loire-37-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Indre-et-Loire (37)</span><br />Voir les groupements"}
			},
			"department-27": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-eure-27-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Eure (27)</span><br />Voir les groupements"}
			},
			"department-80": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-somme-80-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Somme (80)</span><br />Voir les groupements"}
			},
			"department-51": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-marne-51-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Marne (51)</span><br />Voir les groupements"}
			},
			"department-72": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-sarthe-72-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Sarthe (72)</span><br />Voir les groupements"}
			},
			"department-71": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-saone-et-loire-71-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Saône-et-Loire (71)</span><br />Voir les groupements"}
			},
			"department-84": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-vaucluse-84-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Vaucluse (84)</span><br />Voir les groupements"}
			},
			"department-02": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-aisne-02-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Aisne (02)</span><br />Voir les groupements"}
			},
			"department-25": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-doubs-25-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Doubs (25)</span><br />Voir les groupements"}
			},
			"department-21": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-cote-d-or-21-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Côte-d'Or (21)</span><br />Voir les groupements"}
			},
			"department-50": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-manche-50-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Manche (50)</span><br />Voir les groupements"}
			},
			"department-26": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-drome-26-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Drôme (26)</span><br />Voir les groupements"}
			},
			"department-66": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-pyrenees-orientales-66-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Pyrénées-Orientales (66)</span><br />Voir les groupements"}
			},
			"department-28": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-eure-et-loir-28-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Eure-et-Loir (28)</span><br />Voir les groupements"}
			},
			"department-86": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-vienne-86-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Vienne (86)</span><br />Voir les groupements"}
			},
			"department-73": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-savoie-73-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Savoie (73)</span><br />Voir les groupements"}
			},
			"department-24": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-dordogne-24-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Dordogne (24)</span><br />Voir les groupements"}
			},
			"department-40": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-landes-40-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Landes (40)</span><br />Voir les groupements"}
			},
			"department-88": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-vosges-88-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Vosges (88)</span><br />Voir les groupements"}
			},
			"department-81": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-tarn-81-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Tarn (81)</span><br />Voir les groupements"}
			},
			"department-87": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-haute-vienne-87-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Haute-Vienne (87)</span><br />Voir les groupements"}
			},
			"department-79": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-deux-sevres-79-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Deux-Sèvres (79)</span><br />Voir les groupements"}
			},
			"department-11": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-aude-11-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Aude (11)</span><br />Voir les groupements"}
			},
			"department-16": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-charente-16-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Charente (16)</span><br />Voir les groupements"}
			},
			"department-89": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-yonne-89-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Yonne (89)</span><br />Voir les groupements"}
			},
			"department-03": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-allier-03-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Allier (03)</span><br />Voir les groupements"}
			},
			"department-47": {
				value: "5",
				href : "commande-groupee-de-fioul-domestique-lot-et-garonne-47-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Lot-et-Garonne (47)</span><br />Pas de groupement régulier"}
			},
			"department-41": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-loir-et-cher-41-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Loir-et-Cher (41)</span><br />Voir les groupements"}
			},
			"department-07": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-ardeche-07-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Ardèche (07)</span><br />Voir les groupements"}
			},
			"department-18": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-cher-18-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Cher (18)</span><br />Voir les groupements"}
			},
			"department-53": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-mayenne-53-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Mayenne (53)</span><br />Voir les groupements"}
			},
			"department-10": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-aube-10-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Aube (10)</span><br />Voir les groupements"}
			},
			"department-61": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-orne-61-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Orne (61)</span><br />Voir les groupements"}
			},
			"department-08": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-ardennes-08-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Ardennes (08)</span><br />Voir les groupements"}
			},
			"department-12": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-aveyron-12-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Aveyron (12)</span><br />Voir les groupements"}
			},
			"department-39": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-jura-39-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Jura (39)</span><br />Voir les groupements"}
			},
			"department-19": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-corrèze-19-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Corrèze (19)</span><br />Voir les groupements"}
			},
			"department-82": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-tarn-et-garonne-82-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Tarn-et-Garonne (82)</span><br />Voir les groupements"}
			},
			"department-70": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-haute-saone-70-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Haute-Saône (70)</span><br />Voir les groupements"}
			},
			"department-36": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-indre-36-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Indre (36)</span><br />Voir les groupements"}
			},
			"department-65": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-hautes-pyrenees-65-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Hautes-Pyrénées (65)</span><br />Voir les groupements"}
			},
			"department-43": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-haute-loire-43-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Haute-Loire (43)</span><br />Voir les groupements"}
			},
			"department-58": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-nievre-58-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Nièvre (58)</span><br />Voir les groupements"}
			},
			"department-55": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-meuse-55-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Meuse (55)</span><br />Voir les groupements"}
			},
			"department-32": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-gers-32-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Gers (32)</span><br />Voir les groupements"}
			},
			"department-52": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-haute-marne-52-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Haute-Marne (52)</span><br />Voir les groupements"}
			},
			"department-46": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-lot-46-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Lot (46)</span><br />Voir les groupements"}
			},
			"department-04": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-alpes-de-haute-provence-04-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Alpes-de-Haute-Provence (04)</span><br />Voir les groupements"}
			},
			"department-09": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-ariège-09-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Ariège (09)</span><br />Voir les groupements"}
			},
			"department-15": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-cantal-15-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Cantal (15)</span><br />Voir les groupements"}
			},
			"department-90": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-territoire-de-belfort-90-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Territoire de Belfort (90)</span><br />Voir les groupements"}
			},
			"department-05": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-Hautes-alpes-05-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Hautes-Alpes (05)</span><br />Voir les groupements"}
			},
			"department-23": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-creuse-23-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Creuse (23)</span><br />Voir les groupements"}
			},
			"department-48": {
				value: "10",
				href : "commande-groupee-de-fioul-domestique-lozere-48-departement.html",
				tooltip: {content : "<span style=\"font-weight:bold;\">Lozère (48)</span><br />Voir les groupements"}
			}
		}
	});

});
