parted raspbian.img --script -- mklabel msdos
parted raspbian.img --script -- mkpart primary fat32 8192s 204799s
parted raspbian.img --script -- mkpart primary ext4 204800s -1
loopdevice=`losetup -f --show raspbian.img`
device=`kpartx -va $loopdevice | sed -E 's/.*(loop[0-9])p.*/\1/g' | head -1`
device="/dev/mapper/${device}"
partBoot="${device}p1"
partRoot="${device}p2"
mkfs.vfat $partBoot
mkfs.ext4 $partRoot
mount -t vfat $partBoot /mnt
cp -rfp /boot/* /mnt/
# for pidora:
# vim cmdline.txte 
# change root=/dev/mmcblk0p5 to root=/dev/mmcblk0p2
#for raspbian:
# vim cmdline.txt
# change root=/dev/mmcblk0p5 to root=/dev/mmcblk0p2
# don't forget to change fstab!!!
umount /mnt
mount -t ext4 $partRoot /mnt
rsync -aAXv --exclude={"/dev/*","/proc/*","/sys/*","/tmp/*","/run/*","/mnt/*","/media/*","/lost+found","/boot","/root/mnt/log.*"} /* /mnt
umount /mnt
kpartx -d $loopdevice
losetup -d $loopdevice
