			function resize()
			{
				var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
				height *= 0.75;
				document.getElementById("BibleFrame").innerHeight = height;
				document.getElementById("BibleFrame").height = height;
			}