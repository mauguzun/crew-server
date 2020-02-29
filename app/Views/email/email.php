<? include("header.php") ?>

<h1> <?= $title ?></h1>
<h4>crew meeting</h4>
<p>
	We hope you’ve been enjoying our product so far. Have you created a poll and are curious to find out a little more about us? Did you vote on a date in someone else’s poll and now you’re digging a little deeper?

	You’ve managed to find yourself on a very important page!

	Legally-speaking, this is the set of rules that you have to agree to in order to use the product. You’ll find all the usual suspects here; limited-liability, jurisdiction, etc.

	Keep in mind that just by using Doodle, or logging in, you’re automatically agreeing to the following terms.
</p>

<? if (isset($url)) : ?>
	<a style="background: #4dc6e7;color: white;padding:15px;margitn-top:30px;" href="<?= $url ?>">
		<?= $title ?><br>


	</a>
<? endif; ?>
</td>
</tr>
</tbody>
</table>
</div>
<? include("footer.php") ?>
