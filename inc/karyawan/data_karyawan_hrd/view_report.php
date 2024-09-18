<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama PT</th>
                                        <th>Entity</th>
                                        <th>Depo</th>
                                        <th>Nama</th>
                                        <th>PIN</th>
                                        <th>NIK</th>
                                        <th>Kategori Karyawan</th>
                                        <th>Nama Outsourcing</th>
                                        <th>Status Kontrak Kerja</th>
                                        <th>Tgl Akhir PKWT</th>
                                        <th>Status Pengajuan</th>
                                        <th>Last Update</th>
                                        <th class="center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql ="select * from [db_hris].[dbo].[table_karyawan_draft] order by nama_pt,entity,depo,nama asc";
                                        $stmt = $sqlsrv_hris->query($sql);  
                                        $no=1;
                                        while($row = $sqlsrv_hris->fetch_array($stmt)):
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $no;?>.</td>
                                        <td><?php echo $row['nama_pt'];?></td>
                                        <td><?php echo $row['entity'];?></td>
                                        <td><?php echo $row['depo'];?></td>
                                        <td><?php echo $row['nama'];?></td>
                                        <td><?php echo $row['pin'];?></td>
                                        <td>
                                            <a href="?sm=data_karyawan_hrd&detail_page=form_karyawan&act=detail_karyawan&id=<?php echo $row['id'];?>">
                                                <?php echo $row['nik'];?>
                                            </a>
                                        </td>
                                        <td><?php echo $row['kategori_karyawan'];?></td>
                                        <td><?php echo $row['nama_outsourcing'];?></td>
                                        <td><?php echo $row['status_kontrak_kerja'];?></td>
                                        <td><?php echo $row['tgl_akhir_pkwt'] != "" ? date_format($row['tgl_akhir_pkwt'],"Y-m-d") : "";?></td>
                                        <td>
                                        <?php if ($row['status_data'] == "complete"): ?>
                                            <i class="text-success"><?php echo $row['status_data'];?></i>
                                        <?php elseif ($row['status_data'] == "proses"): ?>
                                            <i class="text-warning"><?php echo $row['status_data'];?></i>
                                        <?php else: ?>
                                            <i class="text-danger"><?php echo $row['status_data'];?></i>
                                        <?php endif; ?>
                                        </td>
                                        <td><?php echo $row['tgl_update'] != "" ? date_format($row['tgl_update'],"Y-m-d H:i:s") : "";?></td>
                                        <td class="center" style="width: 100px;">
                                            <div class="btn-group">
                                                <?php if ($row['status_data'] != "complete"): ?>
                                                <a class="btn btn-xs btn-white btn-info" href="?sm=data_karyawan_hrd&detail_page=form_karyawan&act=edit_karyawan&id=<?php echo $row['id'];?>">
                                                <i class="fa fa-check-circle"></i> Verifikasi
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                   <?php
                                        $no++;
                                        endwhile;
                                   ?>
                                </tbody>
                            </table>